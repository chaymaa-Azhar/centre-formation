@extends('layouts.formateur')

@section('title', 'Gestion des Notes - Formateur')
@section('page-title', 'Mes Formations - Saisie des Notes')

@section('content')
<div class="row g-4">
    @forelse($formations as $formation)
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100 hvr-float">
                <div class="card-body d-flex flex-column">
                    <div class="mb-3">
                        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill mb-2">
                            <i class="bi bi-book me-1"></i> {{ $formation->duree }}
                        </span>
                        <h5 class="fw-bold mb-2">{{ $formation->titre }}</h5>
                        <p class="text-muted small text-truncate-2 mb-4">{{ $formation->description }}</p>
                    </div>
                    @php
                        $gradableCount = $formation->etudiants()->whereHas('inscriptions', function($q) use ($formation) {
                            $q->where('formation_id', $formation->id)->where('statut', 'Validé');
                        })->count();
                    @endphp
                    <div class="mt-auto">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="small text-muted"><i class="bi bi-people me-1"></i> {{ $gradableCount }} Étudiants à noter</span>
                        </div>
                        <a href="{{ route('formateur.notes.create', $formation->id) }}" class="btn btn-primary w-100 fw-bold">
                            <i class="bi bi-pencil-square me-2"></i> Gérer les notes
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card border-0 shadow-sm p-5 text-center">
                <i class="bi bi-journals text-muted fs-1 mb-3"></i>
                <h5 class="text-muted">Vous n'avez aucune formation assignée pour le moment.</h5>
            </div>
        </div>
    @endforelse
</div>

<style>
    .hvr-float:hover {
        transform: translateY(-5px);
        transition: transform 0.3s ease;
    }
    .text-truncate-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endsection
