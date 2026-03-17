@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold m-0">Ajouter une Formation</h3>
    <a href="{{ route('admin.formations.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Retour
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <form action="{{ route('admin.formations.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-12">
                    <label class="form-label form-label-required">Titre de la formation</label>
                    <input type="text" name="titre" class="form-control @error('titre') is-invalid @enderror" value="{{ old('titre') }}" required>
                    @error('titre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
                <div class="col-md-4">
                    <label class="form-label form-label-required">Durée</label>
                    <input type="text" name="duree" class="form-control @error('duree') is-invalid @enderror" placeholder="Ex: 3 mois" value="{{ old('duree') }}" required>
                    @error('duree') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label form-label-required">Prix (MAD)</label>
                    <input type="number" step="0.01" name="prix" class="form-control @error('prix') is-invalid @enderror" value="{{ old('prix') }}" required>
                    @error('prix') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label form-label-required">Places disponibles</label>
                    <input type="number" name="places" class="form-control @error('places') is-invalid @enderror" value="{{ old('places') }}" required min="1">
                    @error('places') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-12">
                    <label class="form-label form-label-required">Formateur principal</label>
                    <select name="formateur_id" class="form-select @error('formateur_id') is-invalid @enderror" required>
                        <option value="">Sélectionnez un formateur</option>
                        @foreach($formateurs as $formateur)
                            <option value="{{ $formateur->id }}" {{ old('formateur_id') == $formateur->id ? 'selected' : '' }}>{{ $formateur->nom }} {{ $formateur->prenom }} - {{ $formateur->specialite }}</option>
                        @endforeach
                    </select>
                    @error('formateur_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-12">
                    <label class="form-label">Description & Objectifs</label>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4">{{ old('description') }}</textarea>
                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            
            <div class="mt-4 text-end">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Enregistrer la formation</button>
            </div>
        </form>
    </div>
</div>
@endsection
