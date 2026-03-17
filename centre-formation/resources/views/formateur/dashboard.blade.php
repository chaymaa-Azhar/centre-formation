@extends('layouts.formateur')

@section('title', 'Tableau de bord - Formateur')
@section('page-title', 'Tableau de bord')

@section('content')
<div class="row g-4">
    <!-- Stat Card 1 -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                        <i class="bi bi-book text-primary fs-4"></i>
                    </div>
                    <h6 class="card-title mb-0 fw-bold">Mes Formations</h6>
                </div>
                <h2 class="fw-bold mb-0">{{ $formationsCount }}</h2>
                <p class="text-muted small mb-0 mt-2">Formations actives sous votre responsabilité</p>
            </div>
        </div>
    </div>

    <!-- Stat Card 2 -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
                        <i class="bi bi-people text-success fs-4"></i>
                    </div>
                    <h6 class="card-title mb-0 fw-bold">Mes Étudiants</h6>
                </div>
                <h2 class="fw-bold mb-0">{{ $studentsCount }}</h2>
                <p class="text-muted small mb-0 mt-2">Étudiants inscrits dans vos formations</p>
            </div>
        </div>
    </div>

    <!-- Stat Card 3 -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="rounded-circle bg-info bg-opacity-10 p-3 me-3">
                        <i class="bi bi-calendar-check text-info fs-4"></i>
                    </div>
                    <h6 class="card-title mb-0 fw-bold">Sessions Planifiées</h6>
                </div>
                <h2 class="fw-bold mb-0">{{ $sessionsCount }}</h2>
                <p class="text-muted small mb-0 mt-2">Total des cours programmés</p>
            </div>
        </div>
    </div>

    <!-- Quick Access Section -->
    <div class="col-12 mt-4">
        <h5 class="fw-bold mb-3"><i class="bi bi-lightning-charge me-2 text-warning"></i>Actions Rapides</h5>
        <div class="row g-3">
            <div class="col-md-3">
                <a href="{{ route('formateur.planning') }}" class="btn btn-white border shadow-sm w-100 py-3 fw-bold text-start">
                    <i class="bi bi-calendar3 me-2 text-primary"></i> Voir mon planning
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('formateur.etudiants') }}" class="btn btn-white border shadow-sm w-100 py-3 fw-bold text-start">
                    <i class="bi bi-person-lines-fill me-2 text-success"></i> Liste des étudiants
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('formateur.notes') }}" class="btn btn-white border shadow-sm w-100 py-3 fw-bold text-start">
                    <i class="bi bi-award me-2 text-danger"></i> Saisir des notes
                </a>
            </div>
        </div>
    </div>

    <!-- Welcome Message -->
    <div class="col-12 mt-4">
        <div class="card border-0 shadow-sm bg-primary text-white p-4 overflow-hidden position-relative">
            <div class="position-relative z-1">
                <h3 class="fw-bold">Bonjour, {{ $formateur->prenom }} !</h3>
                <p class="mb-0">Bienvenue dans votre espace dédié. Suivez vos sessions et gérez les notes de vos étudiants en toute simplicité.</p>
            </div>
            <i class="bi bi-mortarboard position-absolute" style="bottom: -20px; right: 20px; font-size: 150px; opacity: 0.1;"></i>
        </div>
    </div>
</div>
@endsection
