@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold m-0">Ajouter un Paiement</h3>
    <a href="{{ route('admin.paiements.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Retour
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <form action="{{ route('admin.paiements.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label form-label-required">Étudiant</label>
                    <select name="etudiant_id" class="form-select @error('etudiant_id') is-invalid @enderror" required>
                        <option value="">Sélectionnez un étudiant</option>
                        @foreach($etudiants as $etudiant)
                            <option value="{{ $etudiant->id }}" {{ old('etudiant_id') == $etudiant->id ? 'selected' : '' }}>{{ $etudiant->nom }} {{ $etudiant->prenom }}</option>
                        @endforeach
                    </select>
                    @error('etudiant_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
                <div class="col-md-6">
                    <label class="form-label form-label-required">Formation</label>
                    <select name="formation_id" class="form-select @error('formation_id') is-invalid @enderror" required>
                        <option value="">Sélectionnez une formation</option>
                        @foreach($formations as $formation)
                            <option value="{{ $formation->id }}" {{ old('formation_id') == $formation->id ? 'selected' : '' }}>{{ $formation->titre }} ({{ number_format($formation->prix, 2) }} MAD)</option>
                        @endforeach
                    </select>
                    @error('formation_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label form-label-required">Montant (MAD)</label>
                    <input type="number" step="0.01" name="montant" class="form-control @error('montant') is-invalid @enderror" value="{{ old('montant') }}" required>
                    @error('montant') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label form-label-required">Mode de paiement</label>
                    <select name="mode_paiement" class="form-select @error('mode_paiement') is-invalid @enderror" required>
                        <option value="">Sélectionnez un mode</option>
                        <option value="Espèces" {{ old('mode_paiement') == 'Espèces' ? 'selected' : '' }}>Espèces</option>
                        <option value="Chèque" {{ old('mode_paiement') == 'Chèque' ? 'selected' : '' }}>Chèque</option>
                        <option value="Virement Bancaire" {{ old('mode_paiement') == 'Virement Bancaire' ? 'selected' : '' }}>Virement Bancaire</option>
                        <option value="Carte Bancaire" {{ old('mode_paiement') == 'Carte Bancaire' ? 'selected' : '' }}>Carte Bancaire</option>
                    </select>
                    @error('mode_paiement') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label form-label-required">Date de paiement</label>
                    <input type="date" name="date_paiement" class="form-control @error('date_paiement') is-invalid @enderror" value="{{ old('date_paiement', date('Y-m-d')) }}" required>
                    @error('date_paiement') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Statut</label>
                    <select name="statut" class="form-select @error('statut') is-invalid @enderror">
                        <option value="Payé" {{ old('statut') == 'Payé' ? 'selected' : '' }}>Payé</option>
                        <option value="En attente" {{ old('statut') == 'En attente' ? 'selected' : '' }}>En attente</option>
                    </select>
                    @error('statut') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            
            <div class="mt-4 text-end">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Enregistrer le paiement</button>
            </div>
        </form>
    </div>
</div>
@endsection
