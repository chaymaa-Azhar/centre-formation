@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold m-0">Gestion des Inscriptions</h3>
</div>

<!-- Filtres -->
<div class="card mb-4 shadow-sm border-0">
    <div class="card-body">
        <form action="{{ route('admin.inscriptions.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Rechercher un étudiant</label>
                <input type="text" name="search" class="form-control" placeholder="Nom, Prénom ou Email" value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Formation</label>
                <select name="formation_id" class="form-select">
                    <option value="">Toutes les formations</option>
                    @foreach($formations as $f)
                        <option value="{{ $f->id }}" {{ request('formation_id') == $f->id ? 'selected' : '' }}>{{ $f->titre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Statut</label>
                <select name="statut" class="form-select">
                    <option value="">Tous</option>
                    <option value="En attente" {{ request('statut') == 'En attente' ? 'selected' : '' }}>En attente</option>
                    <option value="Validé" {{ request('statut') == 'Validé' ? 'selected' : '' }}>Validé</option>
                    <option value="Refusé" {{ request('statut') == 'Refusé' ? 'selected' : '' }}>Refusé</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search me-1"></i> Filtrer</button>
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
                        <th>Date</th>
                        <th>Étudiant</th>
                        <th>Formation</th>
                        <th>Paiement</th>
                        <th>Statut</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($inscriptions as $i)
                    <tr class="{{ $i->created_at->gt(now()->subDay()) ? 'new-row' : '' }}">
                        <td>{{ $i->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <div class="fw-bold">{{ $i->etudiant->nom }} {{ $i->etudiant->prenom }}</div>
                            <small class="text-muted">{{ $i->etudiant->email }}</small>
                        </td>
                        <td>{{ $i->formation->titre }}</td>
                        <td>
                            @if($i->paiement)
                                <div class="fw-bold">{{ number_format($i->paiement->montant, 2) }} MAD</div>
                                <small class="badge bg-{{ $i->paiement->statut == 'Payé' ? 'success' : 'secondary' }} py-1 px-2">
                                    {{ $i->paiement->mode_paiement }} : {{ $i->paiement->statut }}
                                </small>
                            @else
                                <span class="text-muted small">Aucun paiement trouvé</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-{{ $i->statut == 'Validé' ? 'success' : ($i->statut == 'Refusé' ? 'danger' : 'warning text-dark') }}">
                                {{ $i->statut }}
                            </span>
                        </td>
                        <td class="text-end">
                            @if($i->statut == 'En attente')
                            <form action="{{ route('admin.inscriptions.valider', $i->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success"><i class="bi bi-check-lg"></i> Valider</button>
                            </form>
                            <form action="{{ route('admin.inscriptions.refuser', $i->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-x-lg"></i> Refuser</button>
                            </form>
                            @endif
                            <form action="{{ route('admin.inscriptions.destroy', $i->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette inscription ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">Aucune inscription trouvée.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if(isset($inscriptions) && method_exists($inscriptions, 'hasPages') && $inscriptions->hasPages())
    <div class="card-footer bg-white pt-3 border-0">
        {{ $inscriptions->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection
