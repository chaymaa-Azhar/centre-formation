<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Espace Formateur')</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body { background-color: #f4f6f9; }
        .sidebar { min-height: 100vh; background: #2c3e50; color: white; padding-top: 20px; position: fixed; width: 250px;}
        .sidebar a { color: #cfd8dc; text-decoration: none; padding: 12px 20px; display: block; }
        .sidebar a:hover, .sidebar a.active { background: #34495e; color: white; border-radius: 4px;}
        .main-content { margin-left: 250px; width: calc(100% - 250px); }
        .top-navbar { background: white; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
    </style>
    @yield('styles')
</head>
<body>

<div class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar">
        <h4 class="text-center mb-4 px-2 fw-bold text-primary"><i class="bi bi-briefcase me-2"></i>Espace Formateur</h4>
        
        <div class="px-3">
            <a href="{{ route('formateur.dashboard') }}" class="{{ request()->routeIs('formateur.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2 me-2"></i> Tableau de bord
            </a>
            <a href="{{ route('formateur.etudiants') }}" class="{{ request()->routeIs('formateur.etudiants') ? 'active' : '' }}">
                <i class="bi bi-people me-2"></i> Mes Étudiants
            </a>
            <a href="{{ route('formateur.planning') }}" class="{{ request()->routeIs('formateur.planning') ? 'active' : '' }}">
                <i class="bi bi-calendar-event me-2"></i> Mon Planning
            </a>
            <a href="{{ route('formateur.notes') }}" class="{{ request()->routeIs('formateur.notes*') ? 'active' : '' }}">
                <i class="bi bi-journal-check me-2"></i> Gestion des Notes
            </a>
        </div>
        
        <div class="px-3 mt-auto mb-4 w-100" style="position: absolute; bottom: 0;">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-danger w-100 fw-bold border-0 text-start ps-3" style="color: #cfd8dc;">
                    <i class="bi bi-box-arrow-right me-2"></i> Déconnexion
                </button>
            </form>
        </div>
    </div>

    <!-- Contenu -->
    <div class="main-content flex-grow-1">
        <!-- Top Navbar -->
        <nav class="navbar top-navbar px-4 py-3 d-flex justify-content-between">
            <h5 class="m-0 fw-bold">@yield('page-title')</h5>
            <div class="d-flex align-items-center">
                <span class="fw-semibold"><i class="bi bi-person-badge me-1"></i> Formateur : {{ Auth::guard('formateur')->user()->nom }} {{ Auth::guard('formateur')->user()->prenom }}</span>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="p-4">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@yield('scripts')
</body>
</html>
