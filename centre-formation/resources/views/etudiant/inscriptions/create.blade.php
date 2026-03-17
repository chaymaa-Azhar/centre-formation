@extends('layouts.etudiant')

@section('title', 'S\'inscrire à une formation')
@section('page-title', 'Nouvelle Inscription')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-pencil-square me-2 text-primary"></i>Formulaire d'inscription</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('etudiant.inscriptions.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="formation_id" class="form-label fw-bold">Choisir une formation</label>
                        <select name="formation_id" id="formation_id" class="form-select @error('formation_id') is-invalid @enderror" required>
                            <option value="">-- Sélectionner une formation --</option>
                            @foreach($formations as $formation)
                            <option value="{{ $formation->id }}" data-prix="{{ $formation->prix }}" {{ old('formation_id') == $formation->id ? 'selected' : '' }} {{ $formation->places <= 0 ? 'disabled' : '' }}>
                                {{ $formation->titre }} {{ $formation->places <= 0 ? '(COMPLET - Aucune place disponible)' : '' }}
                            </option>
                        @endforeach
                        </select>
                        @error('formation_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div id="payment-section" class="d-none">
                        <hr class="my-4">
                        <h6 class="fw-bold mb-3"><i class="bi bi-credit-card me-2"></i>Détails du paiement</h6>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Montant à régler</label>
                                <div class="input-group">
                                    <input type="number" name="montant" id="montant" class="form-control" readonly>
                                    <span class="input-group-text">MAD</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="mode_paiement" class="form-label fw-bold">Mode de paiement</label>
                                <select name="mode_paiement" id="mode_paiement" class="form-select @error('mode_paiement') is-invalid @enderror" required>
                                    <option value="Espèces">Espèces</option>
                                    <option value="Virement">Virement Bancaire</option>
                                    <option value="Chèque">Chèque</option>
                                </select>
                            </div>
                        </div>

                        <div class="alert alert-info border-0 shadow-sm">
                            <i class="bi bi-info-circle-fill me-2"></i>
                            Une fois le formulaire validé, votre inscription sera marquée "En attente". Elle sera validée par l'administration après réception du paiement.
                        </div>
                    </div>

                    <div class="mt-4 text-end">
                        <a href="{{ route('etudiant.dashboard') }}" class="btn btn-light px-4 me-2">Annuler</a>
                        <button type="submit" class="btn btn-primary px-4 fw-bold">
                            Confirmer mon inscription
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('formation_id').addEventListener('change', function() {
        const paymentSection = document.getElementById('payment-section');
        const montantInput = document.getElementById('montant');
        
        if (this.value) {
            const prix = this.options[this.selectedIndex].getAttribute('data-prix');
            montantInput.value = prix;
            paymentSection.classList.remove('d-none');
        } else {
            paymentSection.classList.add('d-none');
        }
    });
</script>
@endsection
