<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SessionCours;
use App\Models\Formation;
use App\Models\Inscription;
use App\Models\Paiement;
use Carbon\Carbon;

class EtudiantSpaceController extends Controller
{
    public function dashboard()
    {
        $etudiant = Auth::guard('etudiant')->user();
        $inscriptions = $etudiant->inscriptions()->with('formation')->orderBy('created_at', 'desc')->get();
        return view('etudiant.dashboard', compact('etudiant', 'inscriptions'));
    }

    public function planning()
    {
        $etudiant = Auth::guard('etudiant')->user();
        
        // Obtenir les IDs des formations validées pour l'étudiant
        $formationIds = $etudiant->inscriptions()
            ->where('statut', 'Validé')
            ->pluck('formation_id');

        if ($formationIds->isEmpty()) {
            $sessions = collect();
        } else {
            $sessions = SessionCours::with(['formateur', 'formation'])
                ->whereIn('formation_id', $formationIds)
                ->orderBy('date_debut')
                ->orderBy('heure_debut')
                ->get();
        }

        return view('etudiant.planning', compact('etudiant', 'sessions'));
    }

    public function notes()
    {
        $etudiant = Auth::guard('etudiant')->user();
        $notes = $etudiant->notes()->with(['formation', 'formateur'])->get();
        
        return view('etudiant.notes', compact('etudiant', 'notes'));
    }

    public function exportPdf()
    {
        $etudiant = Auth::guard('etudiant')->user();
        $notes = $etudiant->notes()->with(['formation', 'formateur'])->get();
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.releve_notes', compact('etudiant', 'notes'));
        
        $nomFichier = 'releve_notes_' . strtolower(str_replace(' ', '_', $etudiant->nom)) . '_' . strtolower(str_replace(' ', '_', $etudiant->prenom)) . '.pdf';
        
        return $pdf->download($nomFichier);
    }

    public function paiements()
    {
        $etudiant = Auth::guard('etudiant')->user();
        $paiements = $etudiant->paiements()->with('formation')->orderBy('date_paiement', 'desc')->get();
        
        return view('etudiant.paiements', compact('etudiant', 'paiements'));
    }

    public function showInscriptionForm()
    {
        $etudiant = Auth::guard('etudiant')->user();
        $formations = Formation::all();
        
        return view('etudiant.inscriptions.create', compact('etudiant', 'formations'));
    }

    public function storeInscription(Request $request)
    {
        $request->validate([
            'formation_id' => 'required|exists:formations,id',
            'mode_paiement' => 'required|string',
            'montant' => 'required|numeric|min:0',
        ]);

        $etudiant = Auth::guard('etudiant')->user();

        // Vérifier si déjà inscrit (même en attente)
        $exists = Inscription::where('etudiant_id', $etudiant->id)
            ->where('formation_id', $request->formation_id)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Vous avez déjà une demande d\'inscription pour cette formation.');
        }

        $formation = Formation::findOrFail($request->formation_id);

        if ($formation->places <= 0) {
            return back()->withErrors(['formation_id' => 'Désolé, cette formation est complète.'])->withInput();
        }

        // Créer l'inscription
        Inscription::create([
            'etudiant_id' => Auth::guard('etudiant')->id(),
            'formation_id' => $request->formation_id,
            'date_inscription' => Carbon::now(),
            'statut' => 'En attente',
            'source' => 'etudiant',
        ]);

        // Créer le paiement (en attente)
        Paiement::create([
            'etudiant_id' => Auth::guard('etudiant')->id(),
            'formation_id' => $request->formation_id,
            'montant' => $request->montant,
            'mode_paiement' => $request->mode_paiement,
            'date_paiement' => now(),
            'statut' => 'En attente',
        ]);

        // Décrémenter les places
        $formation->decrement('places');

        return redirect()->route('etudiant.dashboard')->with('success', 'Votre demande d\'inscription a été soumise.');
    }
}
