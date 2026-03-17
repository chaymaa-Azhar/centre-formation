<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Espace Étudiant')</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body { background-color: #f4f6f9; }
        .sidebar { min-height: 100vh; background: #343a40; color: white; padding-top: 20px; position: fixed; width: 250px;}
        .sidebar a { color: #cfd8dc; text-decoration: none; padding: 12px 20px; display: block; }
        .sidebar a:hover, .sidebar a.active { background: #495057; color: white; border-radius: 4px;}
        .main-content { margin-left: 250px; width: calc(100% - 250px); }
        .top-navbar { background: white; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
    </style>
    @yield('styles')
</head>
<body>

<div class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar">
        <h4 class="text-center mb-4 px-2 fw-bold text-success"><i class="bi bi-mortarboard me-2"></i>Espace Étudiant</h4>
        
        <div class="px-3">
            <a href="{{ route('etudiant.dashboard') }}" class="{{ request()->routeIs('etudiant.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2 me-2"></i> Tableau de bord
            </a>
            <a href="{{ route('etudiant.planning') }}" class="{{ request()->routeIs('etudiant.planning') ? 'active' : '' }}">
                <i class="bi bi-calendar-event me-2"></i> Mon Planning
            </a>
            <a href="{{ route('etudiant.inscriptions.create') }}" class="{{ request()->routeIs('etudiant.inscriptions.*') ? 'active' : '' }}">
                <i class="bi bi-pencil-square me-2"></i> S'inscrire
            </a>
            <a href="{{ route('etudiant.notes') }}" class="{{ request()->routeIs('etudiant.notes') ? 'active' : '' }}">
                <i class="bi bi-journal-check me-2"></i> Mes Notes
            </a>
            <a href="{{ route('etudiant.paiements') }}" class="{{ request()->routeIs('etudiant.paiements') ? 'active' : '' }}">
                <i class="bi bi-wallet2 me-2"></i> Mes Paiements
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
                <span class="fw-semibold"><i class="bi bi-person-circle me-1"></i> {{ Auth::guard('etudiant')->user()->nom }} {{ Auth::guard('etudiant')->user()->prenom }}</span>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="p-4">
            @yield('content')
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@yield('scripts')
</body>
</html>
