@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold m-0">Gestion des Formateurs</h3>
    <a href="{{ route('admin.formateurs.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Ajouter un Formateur
    </a>
</div>

<!-- Filtres -->
<div class="card mb-4 shadow-sm border-0">
    <div class="card-body">
        <form action="{{ route('admin.formateurs.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-9">
                <label class="form-label">Rechercher</label>
                <input type="text" name="search" class="form-control" placeholder="Nom, Prénom ou Email" value="{{ request('search') }}">
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
                        <th>Nom & Prénom</th>
                        <th>Contact</th>
                        <th>Spécialité & Exp.</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($formateurs as $formateur)
                    <tr>
                        <td class="fw-bold">{{ $formateur->nom }} {{ $formateur->prenom }}</td>
                        <td>
                            <div><i class="bi bi-envelope text-muted me-1"></i>{{ $formateur->email }}</div>
                            <div><i class="bi bi-telephone text-muted me-1"></i>{{ $formateur->telephone ?? 'N/A' }}</div>
                        </td>
                        <td>
                            <div class="fw-semibold text-primary">{{ $formateur->specialite ?? 'N/A' }}</div>
                            <small class="text-muted">{{ $formateur->experience ?? 0 }} an(s) d'expérience</small>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.formateurs.edit', $formateur->id) }}" class="btn btn-sm btn-outline-primary" title="Modifier">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.formateurs.destroy', $formateur->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce formateur ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center text-muted py-4">Aucun formateur trouvé.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if(method_exists($formateurs, 'hasPages') && $formateurs->hasPages())
    <div class="card-footer bg-white pt-3 border-0">
        {{ $formateurs->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection
