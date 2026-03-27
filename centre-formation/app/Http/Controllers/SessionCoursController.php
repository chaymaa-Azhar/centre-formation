<?php

namespace App\Http\Controllers;

use App\Models\SessionCours;
use App\Models\Formation;
use App\Models\Formateur;
use App\Models\Etudiant;
use App\Notifications\SessionUpdated;
use App\Notifications\SessionCreated;
use Illuminate\Http\Request;

class SessionCoursController extends Controller
{
    public function index(Request $request)
    {
        $query = SessionCours::with(['formation', 'formateur']);

        if ($request->filled('formation_id')) {
            $query->where('formation_id', $request->formation_id);
        }

        if ($request->filled('formateur_id')) {
            $query->where('formateur_id', $request->formateur_id);
        }

        if ($request->filled('date_debut')) {
            $query->whereDate('date_debut', '>=', $request->date_debut);
        }
        
        if ($request->filled('date_fin')) {
            $query->whereDate('date_fin', '<=', $request->date_fin);
        }

        $sessions = $query->paginate(10);
        $formations = Formation::all();
        $formateurs = Formateur::all();

        return view('admin.sessions.index', compact('sessions', 'formations', 'formateurs'));
    }

    public function create()
    {
        $formations = Formation::all();
        $formateurs = Formateur::all();
        return view('admin.sessions.create', compact('formations', 'formateurs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'formation_id' => 'required|exists:formations,id',
            'formateur_id' => 'required|exists:formateurs,id',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'heure_debut' => 'required|date_format:H:i',
            'heure_fin' => 'required|date_format:H:i|after:heure_debut',
            'jours' => 'required|array|min:1',
            'jours.*' => 'string|in:Lundi,Mardi,Mercredi,Jeudi,Vendredi,Samedi,Dimanche',
            'matiere' => 'required|string|max:255'
        ]);

        $formation = Formation::findOrFail($request->formation_id);
        $expectedEndDate = $this->calculateExpectedEndDate($request->date_debut, $formation->duree);

        if ($expectedEndDate && !\Carbon\Carbon::parse($request->date_fin)->equalTo($expectedEndDate)) {
            return back()->withErrors(['date_fin' => "La durée de la session doit correspondre à la durée de la formation ({$formation->duree}). Date de fin attendue : " . $expectedEndDate->format('d/m/Y')])->withInput();
        }

        $session = SessionCours::create($validated);

        // Envoyer la notification au formateur
        if ($session->formateur) {
            $session->formateur->notify(new SessionCreated($session, 'formateur'));
        }

        // Envoyer la notification aux étudiants concernés
        $etudiants = Etudiant::whereHas('inscriptions', function($query) use ($session) {
            $query->where('formation_id', $session->formation_id)
                  ->where('statut', 'Validé');
        })->get();

        foreach ($etudiants as $etudiant) {
            $etudiant->notify(new SessionCreated($session, 'etudiant'));
        }

        return redirect()->route('admin.sessions.index')->with('success', 'Session ajoutée avec succès et plannings envoyés.');
    }

    public function edit($id)
    {
        $session = SessionCours::findOrFail($id);
        $formations = Formation::all();
        $formateurs = Formateur::all();
        return view('admin.sessions.edit', compact('session', 'formations', 'formateurs'));
    }

    public function update(Request $request, $id)
    {
        $session = SessionCours::findOrFail($id);
        $validated = $request->validate([
            'formation_id' => 'required|exists:formations,id',
            'formateur_id' => 'required|exists:formateurs,id',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'heure_debut' => 'required|date_format:H:i',
            'heure_fin' => 'required|date_format:H:i|after:heure_debut',
            'jours' => 'required|array|min:1',
            'jours.*' => 'string|in:Lundi,Mardi,Mercredi,Jeudi,Vendredi,Samedi,Dimanche',
            'matiere' => 'required|string|max:255'
        ]);

        $formation = Formation::findOrFail($request->formation_id);
        $expectedEndDate = $this->calculateExpectedEndDate($request->date_debut, $formation->duree);

        if ($expectedEndDate && !\Carbon\Carbon::parse($request->date_fin)->equalTo($expectedEndDate)) {
            return back()->withErrors(['date_fin' => "La durée de la session doit correspondre à la durée de la formation ({$formation->duree}). Date de fin attendue : " . $expectedEndDate->format('d/m/Y')])->withInput();
        }

        // Normaliser les heures et les dates pour éviter que Laravel considère qu'elles ont changé (10:00 vs 10:00:00)
        $validated['heure_debut'] = \Carbon\Carbon::parse($validated['heure_debut'])->format('H:i:s');
        $validated['heure_fin'] = \Carbon\Carbon::parse($validated['heure_fin'])->format('H:i:s');
        $validated['date_debut'] = \Carbon\Carbon::parse($validated['date_debut'])->format('Y-m-d');
        $validated['date_fin'] = \Carbon\Carbon::parse($validated['date_fin'])->format('Y-m-d');

        $session->fill($validated);
        $dirtyChanges = $session->getDirty();
        $session->save();

        // Envoyer la notification au formateur
        if ($session->formateur) {
            $session->formateur->notify(new SessionUpdated($session, 'formateur', $dirtyChanges));
        }

        // Envoyer la notification aux étudiants (inscriptions validées uniquement)
        $etudiants = Etudiant::whereHas('inscriptions', function($query) use ($session) {
            $query->where('formation_id', $session->formation_id)
                  ->where('statut', 'Validé');
        })->get();

        foreach ($etudiants as $etudiant) {
            $etudiant->notify(new SessionUpdated($session, 'etudiant', $dirtyChanges));
        }

        return redirect()->route('admin.sessions.index')->with('success', 'Session modifiée avec succès. Notifications envoyées.');
    }

    private function calculateExpectedEndDate($startDate, $durationString)
    {
        $startDate = \Carbon\Carbon::parse($startDate);
        
        // Normalize string (handling common typos like "moins" for "mois")
        $durationString = str_replace('moins', 'mois', strtolower($durationString));
        
        if (preg_match('/(\d+)\s*(mois|month)/', $durationString, $matches)) {
            return $startDate->copy()->addMonths((int)$matches[1]);
        }
        
        if (preg_match('/(\d+)\s*(jour|day)/', $durationString, $matches)) {
            return $startDate->copy()->addDays((int)$matches[1]);
        }

        if (preg_match('/(\d+)\s*(semaine|week)/', $durationString, $matches)) {
            return $startDate->copy()->addWeeks((int)$matches[1]);
        }

        return null; // Could not parse
    }

    public function destroy($id)
    {
        SessionCours::findOrFail($id)->delete();
        return redirect()->route('admin.sessions.index')->with('success', 'Session supprimée.');
    }
}
