@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold m-0">Modifier la Session</h3>
    <a href="{{ route('admin.sessions.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Retour
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <form action="{{ route('admin.sessions.update', $session->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label form-label-required">Formation</label>
                    <select name="formation_id" class="form-select @error('formation_id') is-invalid @enderror" required>
                        <option value="">Sélectionnez une formation</option>
                        @foreach($formations as $formation)
                            <option value="{{ $formation->id }}" {{ old('formation_id', $session->formation_id) == $formation->id ? 'selected' : '' }}>{{ $formation->titre }} ({{ $formation->duree }})</option>
                        @endforeach
                    </select>
                    @error('formation_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
                <div class="col-md-6">
                    <label class="form-label form-label-required">Formateur</label>
                    <select name="formateur_id" class="form-select @error('formateur_id') is-invalid @enderror" required>
                        <option value="">Sélectionnez un formateur</option>
                        @foreach($formateurs as $formateur)
                            <option value="{{ $formateur->id }}" {{ old('formateur_id', $session->formateur_id) == $formateur->id ? 'selected' : '' }}>{{ $formateur->nom }} {{ $formateur->prenom }} - Spécialité : {{ $formateur->specialite }}</option>
                        @endforeach
                    </select>
                    @error('formateur_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-12">
                    <label class="form-label form-label-required">Matière ou Sujet</label>
                    <input type="text" name="matiere" class="form-control @error('matiere') is-invalid @enderror" value="{{ old('matiere', $session->matiere) }}" required>
                    @error('matiere') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label form-label-required">Date de début</label>
                    <input type="date" name="date_debut" class="form-control @error('date_debut') is-invalid @enderror" value="{{ old('date_debut', date('Y-m-d', strtotime($session->date_debut))) }}" required>
                    @error('date_debut') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label form-label-required">Date de fin</label>
                    <input type="date" name="date_fin" class="form-control @error('date_fin') is-invalid @enderror" value="{{ old('date_fin', date('Y-m-d', strtotime($session->date_fin))) }}" required>
                    @error('date_fin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label form-label-required">Heure de début</label>
                    <input type="time" name="heure_debut" class="form-control @error('heure_debut') is-invalid @enderror" value="{{ old('heure_debut', date('H:i', strtotime($session->heure_debut))) }}" required>
                    @error('heure_debut') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label form-label-required">Heure de fin</label>
                    <input type="time" name="heure_fin" class="form-control @error('heure_fin') is-invalid @enderror" value="{{ old('heure_fin', date('H:i', strtotime($session->heure_fin))) }}" required>
                    @error('heure_fin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-12">
                    <label class="form-label d-block form-label-required">Jours de la semaine</label>
                    <div class="d-flex flex-wrap gap-3 p-3 border rounded @error('jours') border-danger @enderror">
                        @foreach(['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'] as $jour)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="jours[]" value="{{ $jour }}" id="jour_{{ $jour }}" 
                                    {{ (is_array(old('jours')) && in_array($jour, old('jours'))) || (is_array($session->jours) && in_array($jour, $session->jours)) ? 'checked' : '' }}>
                                <label class="form-check-label" for="jour_{{ $jour }}">{{ $jour }}</label>
                            </div>
                        @endforeach
                    </div>
                    @error('jours') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>
            </div>
            
            <div class="mt-4 text-end">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Mettre à jour la session</button>
            </div>
        </form>
    </div>
</div>
@endsection
