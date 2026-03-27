@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold m-0">Gestion des Paiements</h3>
    <a href="{{ route('admin.paiements.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Ajouter un Paiement
    </a>
</div>

<!-- Filtres -->
<div class="card mb-4 shadow-sm border-0">
    <div class="card-body">
        <form action="{{ route('admin.paiements.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-5">
                <label class="form-label">Rechercher</label>
                <input type="text" name="search" class="form-control" placeholder="Nom ou Prénom de l'étudiant" value="{{ request('search') }}">
            </div>
            <div class="col-md-5">
                <label class="form-label">Statut</label>
                <select name="statut" class="form-select">
                    <option value="">Tous les statuts</option>
                    <option value="Payé" {{ request('statut') == 'Payé' ? 'selected' : '' }}>Payé</option>
                    <option value="En attente" {{ request('statut') == 'En attente' ? 'selected' : '' }}>En attente</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-secondary w-100"><i class="bi bi-search me-1"></i> Filtrer</button>
            </div>
        </form>
    </div>
</div>

<!-- Liste -->
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Étudiant & Formation</th>
                        <th>Montant & Mode</th>
                        <th>Date de paiement</th>
                        <th>Statut</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($paiements as $paiement)
                    <tr>
                        <td>
                            <div class="fw-bold">{{ $paiement->etudiant->nom ?? 'N/A' }} {{ $paiement->etudiant->prenom ?? '' }}</div>
                            <small class="text-muted d-block"><i class="bi bi-telephone me-1"></i>{{ $paiement->etudiant->telephone ?? 'N/A' }}</small>
                            <small class="text-muted d-block"><i class="bi bi-book me-1"></i>{{ $paiement->formation->titre ?? 'N/A' }}</small>
                        </td>
                        <td>
                            <div class="fw-semibold text-success">{{ number_format($paiement->montant, 2) }} MAD</div>
                            <small class="text-muted"><i class="bi bi-credit-card me-1"></i>{{ $paiement->mode_paiement }}</small>
                        </td>
                        <td>
                            <i class="bi bi-calendar-check me-1 text-muted"></i>
                            {{ \Carbon\Carbon::parse($paiement->date_paiement)->format('d/m/Y') }}
                        </td>
                        <td>
                            @if($paiement->statut == 'Payé')
                                <span class="badge bg-success">Payé</span>
                            @else
                                <span class="badge bg-warning text-dark">En attente</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.paiements.edit', $paiement->id) }}" class="btn btn-sm btn-outline-primary" title="Modifier">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.paiements.destroy', $paiement->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce paiement ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">Aucun paiement trouvé.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if(method_exists($paiements, 'hasPages') && $paiements->hasPages())
    <div class="card-footer bg-white pt-3 border-0">
        {{ $paiements->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection
