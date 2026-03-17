@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold m-0">Gestion des Sessions</h3>
    <a href="{{ route('admin.sessions.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Planifier une Session
    </a>
</div>

<!-- Filtres -->
<div class="card mb-4 shadow-sm border-0">
    <div class="card-body">
        <form action="{{ route('admin.sessions.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Formation</label>
                <select name="formation_id" class="form-select">
                    <option value="">Toutes les formations</option>
                    @foreach($formations as $f)
                        <option value="{{ $f->id }}" {{ request('formation_id') == $f->id ? 'selected' : '' }}>{{ $f->titre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Formateur</label>
                <select name="formateur_id" class="form-select">
                    <option value="">Tous les formateurs</option>
                    @foreach($formateurs as $f)
                        <option value="{{ $f->id }}" {{ request('formateur_id') == $f->id ? 'selected' : '' }}>{{ $f->nom }} {{ $f->prenom }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Date (à partir de)</label>
                <input type="date" name="date_debut" class="form-control" value="{{ request('date_debut') }}">
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
                        <th>Date & Heure</th>
                        <th>Matière</th>
                        <th>Formation & Formateur</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sessions as $session)
                    <tr>
                        <td>
                            <div class="fw-bold"><i class="bi bi-calendar-event me-1 text-primary"></i>Du {{ \Carbon\Carbon::parse($session->date_debut)->format('d/m/Y') }} au {{ \Carbon\Carbon::parse($session->date_fin)->format('d/m/Y') }}</div>
                            <small class="text-muted d-block"><i class="bi bi-clock me-1 text-warning"></i>{{ \Carbon\Carbon::parse($session->heure_debut)->format('H:i') }} - {{ \Carbon\Carbon::parse($session->heure_fin)->format('H:i') }}</small>
                            <div class="mt-1">
                                @if($session->jours)
                                    @foreach($session->jours as $jour)
                                        <span class="badge bg-light text-dark border p-1" style="font-size: 0.7rem;">{{ $jour }}</span>
                                    @endforeach
                                @endif
                            </div>
                        </td>
                        <td>
                            <span class="fw-semibold">{{ $session->matiere }}</span>
                        </td>
                        <td>
                            <div><span class="badge bg-info text-dark">{{ $session->formation->titre ?? 'N/A' }}</span></div>
                            <small class="text-muted mt-1 d-block"><i class="bi bi-person-badge me-1"></i>{{ $session->formateur->nom ?? 'N/A' }} {{ $session->formateur->prenom ?? '' }}</small>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.sessions.edit', $session->id) }}" class="btn btn-sm btn-outline-primary" title="Modifier">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.sessions.destroy', $session->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette session ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center text-muted py-4">Aucune session trouvée.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if(method_exists($sessions, 'hasPages') && $sessions->hasPages())
    <div class="card-footer bg-white pt-3 border-0">
        {{ $sessions->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection
