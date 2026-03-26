<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Etudiant;
use App\Models\Formateur;
use App\Models\Formation;
use App\Models\Inscription;
use App\Models\Paiement;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalInscriptions = Inscription::count();
        $totalEtudiants = Etudiant::whereHas('inscriptions', function($q) {
            $q->where('statut', 'Validé');
        })->count();
        $totalFormateurs = Formateur::count();
        $totalFormations = Formation::count();

        // Revenus par mois
        $revenusParMois = Paiement::where('statut', 'Payé')
            ->selectRaw('DATE_FORMAT(date_paiement, "%Y-%m") as mois, sum(montant) as total')
            ->groupBy('mois')
            ->orderBy('mois')
            ->get();

        // Nombre Etudiants par formation
        $etudiantsParFormation = Formation::withCount('inscriptions')->get();

        $inscriptionsRecentes = Inscription::with(['etudiant', 'formation'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $paiementsRecents = Paiement::with(['etudiant', 'formation'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalInscriptions',
            'totalEtudiants',
            'totalFormateurs',
            'totalFormations',
            'revenusParMois',
            'etudiantsParFormation',
            'inscriptionsRecentes',
            'paiementsRecents'
        ));
    }
}
