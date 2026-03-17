@extends('layouts.etudiant')

@section('title', 'Espace Étudiant - Dashboard')
@section('page-title', 'Espace Utilisateur')

@section('content')
    <h2 class="mb-4">Bienvenue, {{ $etudiant->prenom }} 👋</h2>
    
    <div class="row">
        <!-- Boîte de récapitulatif -->
    <div class="row">
        <!-- Boîte de récapitulatif -->
        <div class="col-md-5 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title text-muted fw-bold">Votre Profil</h5>
                    <p class="mt-3 text-secondary mb-1"><strong>Email :</strong> {{ $etudiant->email }}</p>
                    <p class="text-secondary mb-0"><strong>Téléphone :</strong> {{ $etudiant->telephone ?? 'Non renseigné' }}</p>
                </div>
            </div>
        </div>
        
        <!-- Boîte d'accueil -->
        <div class="col-md-7 mb-4">
            <div class="card border-0 shadow-sm h-100 bg-primary text-white">
                <div class="card-body d-flex flex-column justify-content-center">
                    <h5><i class="bi bi-info-circle me-2"></i>Bienvenue dans votre espace</h5>
                    <p class="mb-0">Vous pouvez ici suivre l'état de vos inscriptions, consulter votre planning et voir vos notes une fois vos cours terminés.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Mes Inscriptions -->
    <div class="card border-0 shadow-sm mt-2">
        <div class="card-body p-4">
            <h5 class="fw-bold mb-4"><i class="bi bi-journal-check text-primary me-2"></i>Mes Inscriptions</h5>
            
            @if($inscriptions->isEmpty())
                <div class="text-center py-4">
                    <p class="text-muted">Vous n'avez pas encore d'inscription.</p>
                    <a href="{{ route('etudiant.inscriptions.create') }}" class="btn btn-primary btn-sm">
                        S'inscrire à une formation
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Formation</th>
                                <th>Date demande</th>
                                <th>Statut</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($inscriptions as $ins)
                                <tr>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $ins->formation->titre }}</div>
                                        <small class="text-muted">{{ number_format($ins->formation->prix, 2) }} MAD</small>
                                    </td>
                                    <td>{{ $ins->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $ins->statut == 'Validé' ? 'success' : ($ins->statut == 'Refusé' ? 'danger' : 'warning text-dark') }}">
                                            {{ $ins->statut }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        @if($ins->statut == 'Validé')
                                            <a href="{{ route('etudiant.planning') }}" class="btn btn-sm btn-outline-primary">
                                                Voir Planning
                                            </a>
                                        @else
                                            <span class="text-muted small">Aucune action</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection
