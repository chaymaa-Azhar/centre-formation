<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Etudiant;
use App\Models\Formation;
use App\Models\Inscription;
use App\Models\Paiement;
use Carbon\Carbon;
use App\Notifications\AccountCredentialsSent;
use App\Notifications\FormationChanged;

class EtudiantController extends Controller
{
    // Liste des étudiants
    public function index(Request $request)
    {
        $query = Etudiant::with('formation')
            ->whereHas('inscriptions', function($q) {
                $q->where('statut', 'Validé');
            });
        
        if ($request->filled('search')) {
            $search = '%' . $request->search . '%';
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', $search)
                  ->orWhere('prenom', 'like', $search)
                  ->orWhere('email', 'like', $search);
            });
        }
        
        $etudiants = $query->paginate(10);
        return view('admin.etudiants.index', compact('etudiants'));
    }

    public function exportPdf()
    {
        $etudiants = \App\Models\Etudiant::with(['inscriptions.formation'])
            ->whereHas('inscriptions', function($q) {
                $q->where('statut', 'Validé');
            })->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.admin_etudiants', compact('etudiants'));
        return $pdf->download('liste_etudiants_' . date('Ymd') . '.pdf');
    }

    // Formulaire ajout étudiant
    public function create()
    {
        $formations = Formation::all(); // Récupérer toutes les formations
        return view('admin.etudiants.create', compact('formations'));
    }

    // Stocker étudiant f DB
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:etudiants,email',
            'telephone' => 'required|string|max:20',
            'password' => 'required|string|min:6',
            'formation_id' => 'required|exists:formations,id',
            'montant' => 'required|numeric|min:0',
            'mode_paiement' => 'required|string',
            'statut_paiement' => 'required|string|in:Payé,En attente',
        ]);

        $formation = Formation::findOrFail($request->formation_id);

        if ($formation->places <= 0) {
            return back()->withErrors(['formation_id' => 'Désolé, cette formation est complète.'])->withInput();
        }

        $etudiant = Etudiant::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'password' => bcrypt($request->password),
            'formation_id' => $request->formation_id,
        ]);

        // Créer une inscription validée automatiquement avec source 'admin'
        Inscription::create([
            'etudiant_id' => $etudiant->id,
            'formation_id' => $request->formation_id,
            'date_inscription' => Carbon::now(),
            'statut' => 'Validé',
            'source' => 'admin',
        ]);

        // Décrémenter les places
        $formation->decrement('places');

        // Créer le paiement (puisque l'admin l'ajoute directement)
        \App\Models\Paiement::create([
            'etudiant_id' => $etudiant->id,
            'formation_id' => $request->formation_id,
            'montant' => $request->montant,
            'mode_paiement' => $request->mode_paiement,
            'date_paiement' => Carbon::now(),
            'statut' => $request->statut_paiement,
        ]);

        // Envoyer le mot de passe par email
        $etudiant->notify(new AccountCredentialsSent($request->password, true));

        return redirect()->route('admin.etudiants.index')
                         ->with('success', 'Étudiant ajouté avec succès et identifiants envoyés par email.');
    }

    // Formulaire edit étudiant
    public function edit(Etudiant $etudiant)
    {
        $formations = Formation::all();

        return view('admin.etudiants.edit', compact('etudiant', 'formations'));
    }

    // Update étudiant f DB
    public function update(Request $request, Etudiant $etudiant)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:etudiants,email,' . $etudiant->id,
            'telephone' => 'required|string|max:20',
            'formation_id' => 'required|exists:formations,id',
            'password' => 'nullable|string|min:6',
        ]);

        $data = [
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'formation_id' => $request->formation_id,
        ];

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $oldFormationId = $etudiant->formation_id;
        $etudiant->update($data);

        // Envoyer le nouveau mot de passe par email si modifié
        if ($request->filled('password')) {
            $etudiant->notify(new AccountCredentialsSent($request->password, false));
        }

        // Si la formation a changé, on met à jour l'inscription et on notifie
        if ($request->formation_id != $oldFormationId) {
            $oldFormation = Formation::find($oldFormationId);
            $newFormation = Formation::find($request->formation_id);

            // Mettre à jour l'inscription validée correspondante
            $inscription = Inscription::where('etudiant_id', $etudiant->id)
                ->where('formation_id', $oldFormationId)
                ->where('statut', 'Validé')
                ->first();

            if ($inscription) {
                // On libère la place de l'ancienne formation
                $oldFormation->increment('places');
                
                // On décrémente la place de la nouvelle formation
                $newFormation->decrement('places');

                $inscription->update([
                    'formation_id' => $request->formation_id
                ]);

                // Mettre à jour aussi le paiement associé
                Paiement::where('etudiant_id', $etudiant->id)
                    ->where('formation_id', $oldFormationId)
                    ->update(['formation_id' => $request->formation_id]);

                // Notifier l'étudiant
                $etudiant->notify(new FormationChanged($oldFormation, $newFormation));
            }
        }

        return redirect()->route('admin.etudiants.index')
                         ->with('success', 'Étudiant mis à jour avec succès.');
    }

    // Supprimer étudiant
    public function destroy(Etudiant $etudiant)
    {
        // Avant de supprimer, on libère les places de ses inscriptions qui ne sont pas refusées
        foreach ($etudiant->inscriptions as $inscription) {
            if ($inscription->statut != 'Refusé') {
                $inscription->formation->increment('places');
            }
        }

        $etudiant->delete();
        return redirect()->route('admin.etudiants.index')
                         ->with('success', 'Étudiant supprimé et places libérées.');
    }
}