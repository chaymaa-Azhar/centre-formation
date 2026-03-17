<?php

namespace App\Http\Controllers;

use App\Models\Paiement;
use App\Models\Etudiant;
use App\Models\Formation;
use Illuminate\Http\Request;

class PaiementController extends Controller
{
    public function index(Request $request)
    {
        $query = Paiement::with(['etudiant', 'formation'])->orderBy('date_paiement', 'desc');

        if ($request->filled('search')) {
            $query->whereHas('etudiant', function ($q) use ($request) {
                $q->where('nom', 'like', '%' . $request->search . '%')
                  ->orWhere('prenom', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        $paiements = $query->paginate(10);
        return view('admin.paiements.index', compact('paiements'));
    }

    public function create()
    {
        $etudiants = Etudiant::all();
        $formations = Formation::all();
        return view('admin.paiements.create', compact('etudiants', 'formations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'etudiant_id' => 'required|exists:etudiants,id',
            'formation_id' => 'required|exists:formations,id',
            'montant' => 'required|numeric|min:0',
            'mode_paiement' => 'required|string|max:255',
            'date_paiement' => 'required|date',
            'statut' => 'nullable|in:Payé,En attente'
        ]);

        if (!isset($validated['statut'])) {
            $validated['statut'] = 'En attente';
        }

        Paiement::create($validated);
        return redirect()->route('admin.paiements.index')->with('success', 'Paiement ajouté avec succès.');
    }

    public function edit($id)
    {
        $paiement = Paiement::findOrFail($id);
        $etudiants = Etudiant::all();
        $formations = Formation::all();
        return view('admin.paiements.edit', compact('paiement', 'etudiants', 'formations'));
    }

    public function update(Request $request, $id)
    {
        $paiement = Paiement::findOrFail($id);
        $validated = $request->validate([
            'montant' => 'required|numeric|min:0',
            'mode_paiement' => 'required|string|max:255',
            'date_paiement' => 'required|date',
            'statut' => 'required|in:Payé,En attente'
        ]);

        $paiement->update($validated);
        return redirect()->route('admin.paiements.index')->with('success', 'Paiement modifié avec succès.');
    }

    public function destroy($id)
    {
        Paiement::findOrFail($id)->delete();
        return redirect()->route('admin.paiements.index')->with('success', 'Paiement supprimé.');
    }
}
