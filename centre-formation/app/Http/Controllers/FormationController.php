<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Formation;
use App\Models\Formateur;

class FormationController extends Controller
{
    // 1️⃣ Liste des formations avec recherche / filtre
    public function index(Request $request)
    {
        $query = Formation::query();

        // Recherche par titre
        if ($request->search) {
            $query->where('titre', 'like', '%' . $request->search . '%');
        }

        // Filtrer par formateur
        if ($request->formateur_id) {
            $query->where('formateur_id', $request->formateur_id);
        }

        // Filtrer par durée
        if ($request->duree) {
            $query->where('duree', $request->duree);
        }

        // Filtrer par disponibilité (places restantes)
        if ($request->disponible) {
            $query->where('places', '>', 0);
        }

        $formations = Formation::with('etudiants')->paginate(10);      
        $formateurs = Formateur::all(); // pour le filtre
        return view('admin.formations.index', compact('formations', 'formateurs'));
    }

    // 2️⃣ Formulaire pour créer une formation
    public function create()
    {
        $formateurs = Formateur::all(); // pour le select
        return view('admin.formations.create', compact('formateurs'));
    }

    // 3️⃣ Stocker une formation
    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duree' => 'required|string|max:255',
            'prix' => 'required|numeric',
            'places' => 'required|integer|min:0',
            'formateur_id' => 'required|exists:formateurs,id',
        ]);

        Formation::create($request->all());

        return redirect()->route('admin.formations.index')
                         ->with('success', 'Formation ajoutée avec succès.');
    }

    // 4️⃣ Formulaire pour éditer une formation
    public function edit(Formation $formation)
    {
        $formateurs = Formateur::all();
        return view('admin.formations.edit', compact('formation', 'formateurs'));
    }

    // 5️⃣ Mettre à jour une formation
    public function update(Request $request, Formation $formation)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duree' => 'required|string|max:255',
            'prix' => 'required|numeric',
            'places' => 'required|integer|min:0',
            'formateur_id' => 'required|exists:formateurs,id',
        ]);

        $formation->update($request->all());

        return redirect()->route('admin.formations.index')
                         ->with('success', 'Formation mise à jour avec succès.');
    }

    // 6️⃣ Supprimer une formation
    public function destroy(Formation $formation)
    {
        $formation->delete();

        return redirect()->route('admin.formations.index')
                         ->with('success', 'Formation supprimée avec succès.');
    }
}