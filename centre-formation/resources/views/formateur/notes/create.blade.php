@extends('layouts.formateur')

@section('title', 'Saisie des Notes - ' . $formation->titre)
@section('page-title', 'Saisie des Notes')

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h4 class="fw-bold mb-1">{{ $formation->titre }}</h4>
        <p class="text-muted mb-0">Saisie des notes pour les étudiants inscrits et validés.</p>
    </div>
    <a href="{{ route('formateur.notes') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Retour
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4" style="width: 300px;">Étudiant</th>
                        <th>Email</th>
                        <th class="text-center" style="width: 250px;">Note ( / 20 )</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($etudiants as $etudiant)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold">{{ $etudiant->nom }} {{ $etudiant->prenom }}</div>
                            </td>
                            <td>{{ $etudiant->email }}</td>
                            <td class="text-center">
                                <form action="{{ route('formateur.notes.store') }}" method="POST" id="form-note-{{ $etudiant->id }}" class="d-flex align-items-center justify-content-center">
                                    @csrf
                                    <input type="hidden" name="etudiant_id" value="{{ $etudiant->id }}">
                                    <input type="hidden" name="formation_id" value="{{ $formation->id }}">
                                    <div class="input-group" style="width: 150px;">
                                        <input type="number" step="0.25" min="0" max="20" name="valeur" 
                                               class="form-control text-center fw-bold @error('valeur') is-invalid @enderror" 
                                               value="{{ $etudiant->notes->first() ? $etudiant->notes->first()->valeur : '' }}" 
                                               placeholder="--">
                                        <span class="input-group-text small">/20</span>
                                    </div>
                                </form>
                            </td>
                            <td class="text-end pe-4">
                                <button type="submit" form="form-note-{{ $etudiant->id }}" class="btn btn-sm btn-success px-3 fw-bold">
                                    <i class="bi bi-save me-1"></i> Enregistrer
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                Aucun étudiant validé dans cette formation.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@if($errors->any())
    <div class="alert alert-danger mt-4 border-0 shadow-sm">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@endsection
