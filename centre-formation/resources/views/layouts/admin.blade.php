<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Centre de Formation - Admin</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { background-color: #f8f9fa; }
        .sidebar { background-color: #212529; min-height: 100vh; position: fixed; width: 250px; }
        .main-content { margin-left: 250px; padding: 20px; width: calc(100% - 250px); }
        .nav-link { color: rgba(255,255,255,.75); padding: 12px 20px; }
        .nav-link:hover, .nav-link.active { color: #fff; background-color: rgba(255,255,255,.1); }
        .new-row {
            animation: highlight-fade 7s ease-out forwards;
        }
        @keyframes highlight-fade {
            0% { background-color: #fff3cd; } /* Jaune clair très visible */
            100% { background-color: transparent; }
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar text-white shadow">
            <div class="p-3 border-bottom border-secondary d-flex flex-column align-items-center">
                <div class="d-flex align-items-center gap-3 mb-2" style="user-select: none;">
                    <!-- L'image du logo (sera remplacée par une icône si l'image n'est pas encore dans le dossier public) -->
                    <img src="{{ asset('logo.png') }}" alt="Logo" style="height: 40px; width: auto; object-fit: contain;" onerror="this.outerHTML='<i class=\'bi bi-mortarboard-fill text-info\' style=\'font-size: 2.2rem;\'></i>'">
                    
                    <!-- Le texte normal sur une seule ligne -->
                    <h5 class="mb-0 fw-bolder text-white text-nowrap" style="letter-spacing: 0.5px; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">Centre Formation</h5>
                </div>
                
                <span class="badge border border-secondary text-light rounded-pill px-3 mt-1" style="background-color: rgba(255,255,255,0.05); font-size: 0.70rem; letter-spacing: 1px;">
                    ESPACE ADMIN
                </span>
            </div>
            <ul class="nav flex-column py-3">
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2 me-2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.inscriptions.index') }}" class="nav-link {{ request()->routeIs('admin.inscriptions.*') ? 'active' : '' }}">
                        <i class="bi bi-card-checklist me-2"></i> Inscriptions
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.etudiants.index') }}" class="nav-link {{ request()->routeIs('admin.etudiants.*') ? 'active' : '' }}">
                        <i class="bi bi-people me-2"></i> Étudiants
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.formateurs.index') }}" class="nav-link {{ request()->routeIs('admin.formateurs.*') ? 'active' : '' }}">
                        <i class="bi bi-person-badge me-2"></i> Formateurs
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.formations.index') }}" class="nav-link {{ request()->routeIs('admin.formations.*') ? 'active' : '' }}">
                        <i class="bi bi-book me-2"></i> Formations
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.paiements.index') }}" class="nav-link {{ request()->routeIs('admin.paiements.*') ? 'active' : '' }}">
                        <i class="bi bi-wallet2 me-2"></i> Paiements
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.sessions.index') }}" class="nav-link {{ request()->routeIs('admin.sessions.*') ? 'active' : '' }}">
                        <i class="bi bi-calendar-event me-2"></i> Sessions
                    </a>
                </li>
            </ul>

            <div class="px-3 mt-auto mb-4 w-100" style="position: absolute; bottom: 0;">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger w-100 fw-bold">
                        <i class="bi bi-box-arrow-right me-2"></i> Déconnexion
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Content -->
        <div class="main-content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @yield('scripts')
</body>
</html>
