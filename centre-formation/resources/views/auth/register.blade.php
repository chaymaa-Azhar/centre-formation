<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Centre de Formation - Inscription Étudiant</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .register-card {
            max-width: 500px;
            margin: auto;
            margin-top: 50px;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            background: white;
            margin-bottom: 50px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="register-card">
        <h3 class="text-center mb-4 fw-bold text-success"><i class="bi bi-person-plus me-2"></i>Inscription Étudiant</h3>
        
        @if ($errors->any())
            <div class="alert alert-danger">
                Veuillez corriger les erreurs ci-dessous.
            </div>
        @endif

        <form action="{{ route('register.post') }}" method="POST">
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

                <div class="col-md-12">
                    <label class="form-label form-label-required">Adresse Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                
                <div class="col-md-12">
                    <label class="form-label form-label-required">Numéro de Téléphone</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                        <input type="text" name="telephone" class="form-control @error('telephone') is-invalid @enderror" value="{{ old('telephone') }}" required>
                        @error('telephone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="col-md-12">
                    <label class="form-label fw-bold">Choisir une formation</label>
                    <select name="formation_id" id="formation_id" class="form-select @error('formation_id') is-invalid @enderror" required>
                        <option value="">Sélectionnez votre formation</option>
                        @foreach($formations as $formation)
                            <option value="{{ $formation->id }}" data-prix="{{ $formation->prix }}" {{ old('formation_id') == $formation->id ? 'selected' : '' }} {{ $formation->places <= 0 ? 'disabled' : '' }}>
                                {{ $formation->titre }} ({{ $formation->prix }} MAD) {{ $formation->places <= 0 ? '- COMPLET' : '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('formation_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- Section Paiement Dynamique -->
                <div id="payment-section" class="col-md-12 d-none">
                    <div class="card bg-light border-0 p-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-bold">Prix de la formation :</span>
                            <span id="display-price" class="fs-5 fw-bold text-success">0.00 MAD</span>
                        </div>
                        <input type="hidden" name="montant" id="montant-input">
                        
                        <label class="form-label fw-bold small">Mode de paiement souhaité</label>
                        <select name="mode_paiement" class="form-select form-select-sm @error('mode_paiement') is-invalid @enderror" required>
                            <option value="Espèces">Espèces</option>
                            <option value="Virement">Virement Bancaire</option>
                            <option value="Chèque">Chèque</option>
                        </select>
                        @error('mode_paiement') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        
                        <p class="small text-muted mt-2 mb-0">
                            <i class="bi bi-info-circle me-1"></i> Votre inscription sera validée par l'admin après réception du paiement.
                        </p>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Mot de passe</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Confirmer</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>
            </div>

            <button type="submit" class="btn btn-success w-100 fw-bold mt-4 py-2">Créer mon compte et m'inscrire</button>

            <div class="text-center mt-3">
                <p class="mb-0">Déjà inscrit ? <a href="{{ route('login') }}" class="text-decoration-none fw-bold">Se connecter</a></p>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const formationSelect = document.getElementById('formation_id');
    const paymentSection = document.getElementById('payment-section');
    const displayPrice = document.getElementById('display-price');
    const montantInput = document.getElementById('montant-input');

    function updatePrice() {
        if (formationSelect.value) {
            const selectedOption = formationSelect.options[formationSelect.selectedIndex];
            const prix = selectedOption.getAttribute('data-prix');
            displayPrice.innerText = parseFloat(prix).toLocaleString('fr-FR', { minimumFractionDigits: 2 }) + ' MAD';
            montantInput.value = prix;
            paymentSection.classList.remove('d-none');
        } else {
            paymentSection.classList.add('d-none');
        }
    }

    formationSelect.addEventListener('change', updatePrice);
    // Trigger on load if there's an old value
    window.addEventListener('load', updatePrice);
</script>
</body>
</html>
