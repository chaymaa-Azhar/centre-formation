@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold m-0">Gestion des Étudiants</h3>
    <a href="{{ route('admin.etudiants.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Ajouter un Étudiant
    </a>
</div>

<!-- Filtres -->
<div class="card mb-4 shadow-sm border-0">
    <div class="card-body">
        <form action="{{ route('admin.etudiants.index') }}" method="GET" class="row g-3 align-items-end">
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
                        <th>Formation Associée</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($etudiants as $etudiant)
                    <tr>
                        <td class="fw-bold">{{ $etudiant->nom }} {{ $etudiant->prenom }}</td>
                        <td>
                            <div><i class="bi bi-envelope text-muted me-1"></i>{{ $etudiant->email }}</div>
                            <div><i class="bi bi-telephone text-muted me-1"></i>{{ $etudiant->telephone ?? 'N/A' }}</div>
                        </td>
                        <td>
                            @if($etudiant->formation)
                                <span class="badge bg-info text-dark">{{ $etudiant->formation->titre }}</span>
                            @else
                                <span class="badge bg-secondary">Aucune</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.etudiants.edit', $etudiant->id) }}" class="btn btn-sm btn-outline-primary" title="Modifier">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.etudiants.destroy', $etudiant->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet étudiant ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center text-muted py-4">Aucun étudiant trouvé.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if(method_exists($etudiants, 'hasPages') && $etudiants->hasPages())
    <div class="card-footer bg-white pt-3 border-0">
        {{ $etudiants->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection
