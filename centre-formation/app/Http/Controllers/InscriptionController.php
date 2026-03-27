<?php

namespace App\Http\Controllers;

use App\Models\Inscription;
use App\Models\Etudiant;
use App\Models\Formation;
use App\Models\Paiement;
use Illuminate\Http\Request;
use App\Notifications\InscriptionStatusUpdated;

class InscriptionController extends Controller
{
    public function index(Request $request)
    {
        $query = Inscription::with(['etudiant', 'formation'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $query->whereHas('etudiant', function ($q) use ($request) {
                $q->where('nom', 'like', '%' . $request->search . '%')
                  ->orWhere('prenom', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('formation_id')) {
            $query->where('formation_id', $request->formation_id);
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        $inscriptions = $query->paginate(10);

        // Chargement manuel de la relation paiement (composite keys etudiant_id + formation_id)
        $etudiantIds = $inscriptions->pluck('etudiant_id')->unique();
        $formationIds = $inscriptions->pluck('formation_id')->unique();

        $paiements = Paiement::whereIn('etudiant_id', $etudiantIds)
            ->whereIn('formation_id', $formationIds)
            ->get();

        foreach ($inscriptions as $i) {
            $paiement = $paiements->where('etudiant_id', $i->etudiant_id)
                                 ->where('formation_id', $i->formation_id)
                                 ->first();
            $i->setRelation('paiement', $paiement);
        }
        $formations = Formation::all();

        return view('admin.inscriptions.index', compact('inscriptions', 'formations'));
    }

    public function valider($id)
    {
        $inscription = Inscription::findOrFail($id);
        $inscription->update(['statut' => 'Validé']);

        // Notification à l'étudiant
        $inscription->etudiant->notify(new InscriptionStatusUpdated($inscription->formation->titre, 'Validé'));

        return back()->with('success', 'Inscription validée avec succès et notification envoyée.');
    }

    public function refuser($id)
    {
        $inscription = Inscription::findOrFail($id);
        $inscription->update(['statut' => 'Refusé']);

        // Notification à l'étudiant
        $inscription->etudiant->notify(new InscriptionStatusUpdated($inscription->formation->titre, 'Refusé'));

        // Libérer la place
        $inscription->formation->increment('places');

        return redirect()->route('admin.inscriptions.index')->with('success', 'Inscription refusée, place libérée et notification envoyée.');
    }

    public function destroy($id)
    {
        $inscription = Inscription::findOrFail($id);
        
        // On ne libère la place que si elle n'était pas déjà refusée (pour éviter double compte)
        if ($inscription->statut != 'Refusé') {
            $inscription->formation->increment('places');
        }

        $inscription->delete();
        return redirect()->route('admin.inscriptions.index')->with('success', 'Inscription supprimée et place libérée.');
    }
}
