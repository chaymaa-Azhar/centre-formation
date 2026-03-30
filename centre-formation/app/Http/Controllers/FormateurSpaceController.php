<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\NoteAssigned;

class FormateurSpaceController extends Controller
{
    public function dashboard()
    {
        $formateur = Auth::guard('formateur')->user();
        $formationsCount = $formateur->formations()->count();
        $sessionsCount = $formateur->sessions()->count();
        $studentsCount = \App\Models\Etudiant::whereHas('inscriptions', function($q) use ($formateur) {
                $q->whereIn('formation_id', $formateur->formations()->pluck('id'))
                  ->where('statut', 'Validé');
            })->count();
        
        return view('formateur.dashboard', compact('formateur', 'formationsCount', 'sessionsCount', 'studentsCount'));
    }

    public function etudiants()
    {
        $formateur = Auth::guard('formateur')->user();
        $etudiants = \App\Models\Etudiant::whereHas('inscriptions', function($q) use ($formateur) {
                $q->whereIn('formation_id', $formateur->formations()->pluck('id'))
                  ->where('statut', 'Validé');
            })
            ->with(['inscriptions' => function($q) use ($formateur) {
                $q->whereIn('formation_id', $formateur->formations()->pluck('id'))
                  ->where('statut', 'Validé');
            }, 'inscriptions.formation'])
            ->paginate(15);
            
        return view('formateur.etudiants', compact('etudiants'));
    }

    public function exportEtudiantsPdf()
    {
        $formateur = Auth::guard('formateur')->user();
        $etudiants = \App\Models\Etudiant::whereHas('inscriptions', function($q) use ($formateur) {
                $q->whereIn('formation_id', $formateur->formations()->pluck('id'))
                  ->where('statut', 'Validé');
            })
            ->with(['inscriptions' => function($q) use ($formateur) {
                $q->whereIn('formation_id', $formateur->formations()->pluck('id'))
                  ->where('statut', 'Validé');
            }, 'inscriptions.formation'])
            ->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.formateur_etudiants', compact('formateur', 'etudiants'));
        $nomFichier = 'mes_etudiants_' . strtolower(str_replace(' ', '_', $formateur->nom)) . '_' . date('Ymd') . '.pdf';
        return $pdf->download($nomFichier);
    }

    public function planning()
    {
        $formateur = Auth::guard('formateur')->user();
        $sessions = $formateur->sessions()->with('formation')->orderBy('date_debut', 'asc')->get();
        return view('formateur.planning', compact('sessions'));
    }

    public function notes()
    {
        $formateur = Auth::guard('formateur')->user();
        $formations = $formateur->formations;
        return view('formateur.notes.index', compact('formations'));
    }

    public function showNotesForm($formation_id)
    {
        $formateur = Auth::guard('formateur')->user();
        $formation = $formateur->formations()->findOrFail($formation_id);
        
        // On ne gère les notes que pour les étudiants dont l'inscription est validée pour CETTE formation
        $etudiants = \App\Models\Etudiant::whereHas('inscriptions', function($q) use ($formation_id) {
                $q->where('formation_id', $formation_id)->where('statut', 'Validé');
            })
            ->with(['notes' => function($q) use ($formateur, $formation_id) {
                $q->where('formateur_id', $formateur->id)->where('formation_id', $formation_id);
            }])
            ->get();
        
        return view('formateur.notes.create', compact('formation', 'etudiants'));
    }

    public function storeNote(Request $request)
    {
        $request->validate([
            'etudiant_id' => 'required|exists:etudiants,id',
            'formation_id' => 'required|exists:formations,id',
            'valeur' => 'required|numeric|min:0|max:20',
        ]);

        $formateur = Auth::guard('formateur')->user();

        // Vérifier que le formateur est bien rattaché à cette formation
        if (!$formateur->formations()->where('id', $request->formation_id)->exists()) {
            return back()->with('error', 'Action non autorisée.');
        }

        $note = \App\Models\Note::updateOrCreate(
            [
                'etudiant_id' => $request->etudiant_id,
                'formation_id' => $request->formation_id,
                'formateur_id' => $formateur->id,
            ],
            ['valeur' => $request->valeur]
        );

        // Notification à l'étudiant
        $etudiant = \App\Models\Etudiant::find($request->etudiant_id);
        $formation = \App\Models\Formation::find($request->formation_id);
        $etudiant->notify(new NoteAssigned($formation->titre, $request->valeur));

        return back()->with('success', 'Note enregistrée avec succès et notification envoyée.');
    }
}
