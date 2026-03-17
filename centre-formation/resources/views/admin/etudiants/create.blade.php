@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold m-0">Ajouter un Étudiant</h3>
    <a href="{{ route('admin.etudiants.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Retour
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <form action="{{ route('admin.etudiants.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label form-label-required">Nom</label>
                    <input type="text" name="nom" class="form-control @error('nom') is-invalid @enderror" value="{{ old('nom') }}" required>
                    @error('nom') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label form-label-required">Prénom</label>
                    <input type="text" name="prenom" class="form-control @error('prenom') is-invalid @enderror" value="{{ old('prenom') }}" required>
                    @error('prenom') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label form-label-required">Email</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Téléphone</label>
                    <input type="text" name="telephone" class="form-control @error('telephone') is-invalid @enderror" value="{{ old('telephone') }}">
                    @error('telephone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label form-label-required">Mot de passe</label>
                    <div class="input-group">
                        <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required>
                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                            <i class="bi bi-eye"></i>
                        </button>
                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label form-label-required">Formation (par défaut)</label>
                    <select name="formation_id" id="formation_id" class="form-select @error('formation_id') is-invalid @enderror" required>
                        <option value="">Sélectionnez une formation</option>
                        @foreach($formations as $formation)
                            <option value="{{ $formation->id }}" data-prix="{{ $formation->prix }}" {{ old('formation_id') == $formation->id ? 'selected' : '' }} {{ $formation->places <= 0 ? 'disabled' : '' }}>
                                {{ $formation->titre }} - {{ number_format($formation->prix, 2) }} MAD {{ $formation->places <= 0 ? '(COMPLET)' : '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('formation_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="row g-3 mt-2 border-top pt-3">
                <div class="col-12">
                    <h6 class="fw-bold text-primary"><i class="bi bi-cash-stack me-1"></i> Informations de Paiement (Initial)</h6>
                </div>
                <div class="col-md-6">
                    <label class="form-label form-label-required">Montant payé (MAD)</label>
                    <input type="number" step="0.01" name="montant" id="montant" class="form-control @error('montant') is-invalid @enderror" value="{{ old('montant') }}" required>
                    @error('montant') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label form-label-required">Mode de paiement</label>
                    <select name="mode_paiement" class="form-select @error('mode_paiement') is-invalid @enderror" required>
                        <option value="Espèces" {{ old('mode_paiement') == 'Espèces' ? 'selected' : '' }}>Espèces</option>
                        <option value="Virement" {{ old('mode_paiement') == 'Virement' ? 'selected' : '' }}>Virement</option>
                        <option value="Chèque" {{ old('mode_paiement') == 'Chèque' ? 'selected' : '' }}>Chèque</option>
                    </select>
                    @error('mode_paiement') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="mt-4 text-end">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Enregistrer l'étudiant</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Toggle Password
    document.getElementById('togglePassword').addEventListener('click', function (e) {
        const password = document.getElementById('password');
        const icon = this.querySelector('i');
        if (password.type === 'password') {
            password.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            password.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    });

    // Auto-fill price
    const formationSelect = document.getElementById('formation_id');
    const montantInput = document.getElementById('montant');

    formationSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const prix = selectedOption.getAttribute('data-prix');
        if (prix) {
            montantInput.value = prix;
        }
    });
</script>
@endsection
