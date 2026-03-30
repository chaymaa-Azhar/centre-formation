<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Formateur;
use App\Notifications\AccountCredentialsSent;

class FormateurController extends Controller
{
    // 1️⃣ Liste des formateurs avec recherche
    public function index(Request $request)
    {
        $query = Formateur::query();

        // Recherche par nom ou email
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('nom', 'like', '%' . $request->search . '%')
                  ->orWhere('prenom', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $formateurs = $query->paginate(10);
        return view('admin.formateurs.index', compact('formateurs'));
    }

    // 2️⃣ Formulaire pour créer un formateur
    public function create()
    {
        return view('admin.formateurs.create');
    }

    // 3️⃣ Stocker formateur dans la base
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:formateurs,email',
            'telephone' => 'nullable|string|max:20',
            'specialite' => 'nullable|string|max:255',
            'experience' => 'nullable|integer|min:0',
            'password' => 'required|string|min:6',
        ]);

        $data = $request->all();
        $data['password'] = bcrypt($request->password);

        $formateur = Formateur::create($data);

        // Envoyer un email avec les identifiants
        $formateur->notify(new AccountCredentialsSent($request->password, true));

        return redirect()->route('admin.formateurs.index')
                         ->with('success', 'Formateur ajouté avec succès.');
    }

    public function edit(Formateur $formateur)
    {
        return view('admin.formateurs.edit', compact('formateur'));
    }

    // 5️⃣ Mettre à jour le formateur
    public function update(Request $request, Formateur $formateur)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:formateurs,email,' . $formateur->id,
            'telephone' => 'nullable|string|max:20',
            'specialite' => 'nullable|string|max:255',
            'experience' => 'nullable|integer|min:0',
            'password' => 'nullable|string|min:6',
        ]);

        $data = $request->except('password');
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $formateur->update($data);

        // Si le mot de passe a été modifié, envoyer une notification
        if ($request->filled('password')) {
            $formateur->notify(new AccountCredentialsSent($request->password, false));
        }

        return redirect()->route('admin.formateurs.index')
                         ->with('success', 'Formateur mis à jour avec succès.');
    }

    // 6️⃣ Supprimer un formateur
    public function destroy(Formateur $formateur)
    {
        $formateur->delete();

        return redirect()->route('admin.formateurs.index')
                         ->with('success', 'Formateur supprimé avec succès.');
    }
}