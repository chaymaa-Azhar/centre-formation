@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold m-0">Gestion des Formations</h3>
    <a href="{{ route('admin.formations.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Ajouter une Formation
    </a>
</div>

<!-- Filtres -->
<div class="card mb-4 shadow-sm border-0">
    <div class="card-body">
        <form action="{{ route('admin.formations.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Titre</label>
                <input type="text" name="search" class="form-control" placeholder="Titre de la formation" value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Formateur</label>
                <select name="formateur_id" class="form-select">
                    <option value="">Tous les formateurs</option>
                    @foreach($formateurs as $f)
                        <option value="{{ $f->id }}" {{ request('formateur_id') == $f->id ? 'selected' : '' }}>{{ $f->nom }} {{ $f->prenom }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Disponibilité</label>
                <select name="disponible" class="form-select">
                    <option value="">Tous</option>
                    <option value="1" {{ request('disponible') ? 'selected' : '' }}>Places disponibles</option>
                </select>
            </div>
            <div class="col-md-3">
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
                        <th>Titre / Description</th>
                        <th>Détails (Durée/Prix)</th>
                        <th>Places & Formateur</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($formations as $formation)
                    <tr>
                        <td>
                            <div class="fw-bold text-primary">{{ $formation->titre }}</div>
                            <small class="text-muted d-block text-truncate" style="max-width: 250px;">{{ $formation->description ?? 'Aucune description' }}</small>
                        </td>
                        <td>
                            <div><i class="bi bi-clock text-muted me-1"></i>{{ $formation->duree }}</div>
                            <div class="fw-semibold text-success"><i class="bi bi-tag text-muted me-1"></i>{{ number_format($formation->prix, 2) }} MAD</div>
                        </td>
                        <td>
                            <div>
                                <span class="badge {{ $formation->places > 0 ? 'bg-success' : 'bg-danger' }}">
                                    {{ $formation->places }} places restantes
                                </span>
                            </div>
                            <div class="mt-1 small"><i class="bi bi-person text-muted me-1"></i>{{ $formation->formateur->nom }} {{ $formation->formateur->prenom }}</div>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.formations.edit', $formation->id) }}" class="btn btn-sm btn-outline-primary" title="Modifier">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.formations.destroy', $formation->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette formation ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center text-muted py-4">Aucune formation trouvée.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if(method_exists($formations, 'hasPages') && $formations->hasPages())
    <div class="card-footer bg-white pt-3 border-0">
        {{ $formations->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection
