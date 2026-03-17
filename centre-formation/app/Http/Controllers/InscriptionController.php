<?php

namespace App\Http\Controllers;

use App\Models\Inscription;
use App\Models\Etudiant;
use App\Models\Formation;
use Illuminate\Http\Request;

class InscriptionController extends Controller
{
    public function index(Request $request)
    {
        $query = Inscription::with(['etudiant', 'formation', 'paiement'])
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
        $formations = Formation::all();

        return view('admin.inscriptions.index', compact('inscriptions', 'formations'));
    }

    public function valider($id)
    {
        $inscription = Inscription::findOrFail($id);
        $inscription->update(['statut' => 'Validé']);
        return back()->with('success', 'Inscription validée avec succès.');
    }

    public function refuser($id)
    {
        $inscription = Inscription::findOrFail($id);
        $inscription->update(['statut' => 'Refusé']);

        // Libérer la place
        $inscription->formation->increment('places');

        return redirect()->route('admin.inscriptions.index')->with('success', 'Inscription refusée et place libérée.');
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
