<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Etudiant;
use App\Models\Formation;
use App\Models\Inscription;
use App\Models\Paiement;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'role' => 'required|in:admin,etudiant,formateur'
        ]);

        $credentials = $request->only('email', 'password');

        if ($request->role == 'admin') {
            if (Auth::guard('web')->attempt($credentials)) {
                $request->session()->regenerate();
                return redirect()->route('admin.dashboard');
            }
        } elseif ($request->role == 'etudiant') {
            if (Auth::guard('etudiant')->attempt($credentials)) {
                $request->session()->regenerate();
                return redirect()->route('etudiant.dashboard');
            }
        } elseif ($request->role == 'formateur') {
            if (Auth::guard('formateur')->attempt($credentials)) {
                $request->session()->regenerate();
                return redirect()->route('formateur.dashboard');
            }
        }

        return back()->withErrors([
            'email' => 'Les identifiants ne correspondent pas ou le rôle est incorrect.',
        ])->withInput($request->only('email', 'role'));
    }

    public function showRegister()
    {
        $formations = Formation::all();
        return view('auth.register', compact('formations'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:etudiants,email',
            'password' => 'required|string|min:6|confirmed',
            'formation_id' => 'required|exists:formations,id',
            'mode_paiement' => 'required|string',
            'montant' => 'required|numeric|min:0',
        ]);

        $formation = Formation::findOrFail($request->formation_id);

        if ($formation->places <= 0) {
            return back()->withErrors(['formation_id' => 'Désolé, cette formation est complète.'])->withInput();
        }

        $etudiant = Etudiant::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'formation_id' => $request->formation_id,
        ]);

        // Créer l'inscription "En attente" pour cet étudiant et cette formation
        Inscription::create([
            'etudiant_id' => $etudiant->id,
            'formation_id' => $request->formation_id,
            'date_inscription' => Carbon::now(),
            'statut' => 'En attente',
        ]);

        // Créer le paiement initial (en attente)
        Paiement::create([
            'etudiant_id' => $etudiant->id,
            'formation_id' => $request->formation_id,
            'montant' => $request->montant,
            'mode_paiement' => $request->mode_paiement,
            'date_paiement' => Carbon::now(),
            'statut' => 'En attente',
        ]);

        // Décrémenter les places disponibles
        $formation->decrement('places');

        return redirect()->route('login')->with('success', 'Votre compte a été créé avec succès. Vous pouvez maintenant vous connecter.');
    }

    public function logout(Request $request)
    {
        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
        } elseif (Auth::guard('etudiant')->check()) {
            Auth::guard('etudiant')->logout();
        } elseif (Auth::guard('formateur')->check()) {
            Auth::guard('formateur')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
