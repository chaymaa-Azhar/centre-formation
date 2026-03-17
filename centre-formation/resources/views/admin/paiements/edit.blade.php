@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold m-0">Modifier le Paiement</h3>
    <a href="{{ route('admin.paiements.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Retour
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <!-- Informations non modifiables -->
        <div class="alert alert-info border-0 shadow-sm mb-4">
            <h6 class="fw-bold mb-2">Détails de l'étudiant et de la formation</h6>
            <div class="row">
                <div class="col-md-6">
                    <strong>Étudiant :</strong> {{ $paiement->etudiant->nom ?? 'N/A' }} {{ $paiement->etudiant->prenom ?? '' }}
                </div>
                <div class="col-md-6">
                    <strong>Formation :</strong> {{ $paiement->formation->titre ?? 'N/A' }}
                </div>
            </div>
        </div>

        <form action="{{ route('admin.paiements.update', $paiement->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label form-label-required">Montant (MAD)</label>
                    <input type="number" step="0.01" name="montant" class="form-control @error('montant') is-invalid @enderror" value="{{ old('montant', $paiement->montant) }}" required>
                    @error('montant') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label form-label-required">Mode de paiement</label>
                    <select name="mode_paiement" class="form-select @error('mode_paiement') is-invalid @enderror" required>
                        <option value="">Sélectionnez un mode</option>
                        <option value="Espèces" {{ old('mode_paiement', $paiement->mode_paiement) == 'Espèces' ? 'selected' : '' }}>Espèces</option>
                        <option value="Chèque" {{ old('mode_paiement', $paiement->mode_paiement) == 'Chèque' ? 'selected' : '' }}>Chèque</option>
                        <option value="Virement Bancaire" {{ old('mode_paiement', $paiement->mode_paiement) == 'Virement Bancaire' ? 'selected' : '' }}>Virement Bancaire</option>
                        <option value="Carte Bancaire" {{ old('mode_paiement', $paiement->mode_paiement) == 'Carte Bancaire' ? 'selected' : '' }}>Carte Bancaire</option>
                    </select>
                    @error('mode_paiement') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label form-label-required">Date de paiement</label>
                    <input type="date" name="date_paiement" class="form-control @error('date_paiement') is-invalid @enderror" value="{{ old('date_paiement', date('Y-m-d', strtotime($paiement->date_paiement))) }}" required>
                    @error('date_paiement') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label form-label-required">Statut</label>
                    <select name="statut" class="form-select @error('statut') is-invalid @enderror" required>
                        <option value="Payé" {{ old('statut', $paiement->statut) == 'Payé' ? 'selected' : '' }}>Payé</option>
                        <option value="En attente" {{ old('statut', $paiement->statut) == 'En attente' ? 'selected' : '' }}>En attente</option>
                    </select>
                    @error('statut') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            
            <div class="mt-4 text-end">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Mettre à jour</button>
            </div>
        </form>
    </div>
</div>
@endsection
