@extends('layouts.formateur')

@section('title', 'Mes Étudiants - Formateur')
@section('page-title', 'Liste de mes Étudiants')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Étudiant</th>
                        <th>Formation</th>
                        <th>Email</th>
                        <th class="text-center">Note Actuelle</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($etudiants as $etudiant)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-secondary bg-opacity-10 p-2 me-2">
                                        <i class="bi bi-person text-secondary"></i>
                                    </div>
                                    <span class="fw-bold">{{ $etudiant->nom }} {{ $etudiant->prenom }}</span>
                                </div>
                            </td>
                            <td>
                                @foreach($etudiant->inscriptions as $ins)
                                    <div class="badge bg-info text-dark mb-1">{{ $ins->formation->titre }}</div>
                                @endforeach
                            </td>
                            <td>{{ $etudiant->email }}</td>
                            <td class="text-center">
                                @foreach($etudiant->inscriptions as $ins)
                                    @php
                                        $note = $etudiant->notes->where('formation_id', $ins->formation_id)->first();
                                    @endphp
                                    <div class="mb-1">
                                        @if($note)
                                            <span class="badge {{ $note->valeur >= 10 ? 'bg-success' : 'bg-danger' }} rounded-pill p-1 px-2 small">
                                                {{ $ins->formation->titre }} : {{ number_format($note->valeur, 2) }}
                                            </span>
                                        @else
                                            <span class="text-muted extra-small">{{ $ins->formation->titre }} : Non noté</span>
                                        @endif
                                    </div>
                                @endforeach
                            </td>
                            <td class="text-end pe-4">
                                @foreach($etudiant->inscriptions as $ins)
                                    <a href="{{ route('formateur.notes.create', $ins->formation_id) }}" class="btn btn-xs btn-outline-primary mb-1" title="Noter {{ $ins->formation->titre }}">
                                        <i class="bi bi-award"></i> {{ $ins->formation->titre }}
                                    </a>
                                @endforeach
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <i class="bi bi-people text-muted fs-1 d-block mb-3"></i>
                                <p class="text-muted">Aucun étudiant n'est inscrit dans vos formations pour le moment.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($etudiants->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            {{ $etudiants->links() }}
        </div>
    @endif
</div>
@endsection
