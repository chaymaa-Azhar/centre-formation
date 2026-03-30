<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Espace Formateur')</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <script>
        (function () {
            const theme = localStorage.getItem('theme') || 'auto';
            const getPreferredTheme = () => {
                if (theme !== 'auto') return theme;
                return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            };
            document.documentElement.setAttribute('data-bs-theme', getPreferredTheme());
        })();
    </script>
    <style>
        [data-bs-theme="light"] body { background-color: #f4f6f9; }
        [data-bs-theme="light"] .main-content .table thead th { background-color: #f8f9fa !important; }
        [data-bs-theme="dark"] body { background-color: #1a1d20; }
        .sidebar { min-height: 100vh; background: #2c3e50; color: white; padding-top: 20px; position: fixed; width: 250px; transition: background 0.3s; }
        .theme-toggle.active { background-color: rgba(0,0,0,0.1); border-color: rgba(0,0,0,0.2) !important; color: #0d6efd !important; }
        [data-bs-theme="dark"] .theme-toggle.active { background-color: rgba(255,255,255,0.1); border-color: rgba(255,255,255,0.2) !important; color: #3498db !important; }

        /* Nuanced Dark Mode (Smart Selector Version) */
        [data-bs-theme="dark"] .main-content {
            background-color: #121212 !important;
        }

        /* 1. Standard Dark Containers */
        [data-bs-theme="dark"] .main-content .card,
        [data-bs-theme="dark"] .main-content .bg-white { 
            background-color: #212529 !important; 
            color: #ced4da !important;
            border-color: #373b3e !important;
        }

        /* 2. Intelligent Table Support */
        [data-bs-theme="dark"] .main-content .table {
            --bs-table-color: #ced4da;
            --bs-table-bg: #212529;
            --bs-table-border-color: #373b3e;
            color: #ced4da !important;
        }
        [data-bs-theme="dark"] .main-content .table td { 
            color: #ced4da !important; 
        }

        /* 3. Nuanced Light Gray Areas (ONLY when specified) */
        [data-bs-theme="dark"] .main-content .bg-light,
        [data-bs-theme="dark"] .main-content .table-secondary,
        [data-bs-theme="dark"] .main-content .table-light,
        [data-bs-theme="dark"] .main-content .card-header.bg-secondary { 
            --bs-table-color: #000000 !important;
            --bs-table-bg: #ced4da !important;
            background-color: #ced4da !important; 
            color: #000000 !important; 
        }

        /* 🎯 SMART FIX: Force black text ONLY on light backgrounds */
        [data-bs-theme="dark"] .main-content .bg-light th,
        [data-bs-theme="dark"] .main-content .bg-light div:not(.table-responsive),
        [data-bs-theme="dark"] .main-content .bg-light span,
        [data-bs-theme="dark"] .main-content .bg-light p,
        [data-bs-theme="dark"] .main-content .table-light th,
        [data-bs-theme="dark"] .main-content .table-light td,
        [data-bs-theme="dark"] .main-content .table-secondary th,
        [data-bs-theme="dark"] .main-content .table-secondary td,
        [data-bs-theme="dark"] .main-content .card-header.bg-secondary * { 
            color: #000000 !important; 
        }

        /* 🚀 FINAL REFINEMENT: Force dark rows to have light text even inside light cards */
        [data-bs-theme="dark"] .main-content table:not(.table-light):not(.table-secondary) tbody td,
        [data-bs-theme="dark"] .main-content table:not(.table-light):not(.table-secondary) tbody th,
        [data-bs-theme="dark"] .main-content table:not(.table-light):not(.table-secondary) tbody * {
            color: #ced4da !important;
        }

        /* 💡 Badge & Highlight Contrast Fix (Ultra-Specific for Table Bodies) */
        [data-bs-theme="dark"] .main-content table tbody .badge.bg-warning,
        [data-bs-theme="dark"] .main-content table tbody .badge.bg-info,
        [data-bs-theme="dark"] .main-content table tbody .badge.bg-light,
        [data-bs-theme="dark"] .main-content table tbody .badge.bg-secondary,
        [data-bs-theme="dark"] .main-content table tbody .bg-warning,
        [data-bs-theme="dark"] .main-content table tbody .bg-info,
        [data-bs-theme="dark"] .main-content table tbody .badge.bg-warning *,
        [data-bs-theme="dark"] .main-content table tbody .badge.bg-info *,
        [data-bs-theme="dark"] .main-content table tbody .bg-warning *,
        [data-bs-theme="dark"] .main-content table tbody .bg-info * {
            color: #000000 !important;
        }

        /* 4. Inputs & Selects */
        [data-bs-theme="dark"] .main-content .form-control,
        [data-bs-theme="dark"] .main-content .form-select {
            background-color: #2b3035 !important;
            color: #ced4da !important;
            border-color: #495057 !important;
        }
        [data-bs-theme="dark"] .main-content .form-control::placeholder {
            color: #adb5bd !important;
        }
        .sidebar a { color: #cfd8dc; text-decoration: none; padding: 12px 20px; display: block; }
        .sidebar a:hover, .sidebar a.active { background: #34495e; color: white; border-radius: 4px;}
        .main-content { margin-left: 250px; width: calc(100% - 250px); }
        .top-navbar { background: white; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        [data-bs-theme="dark"] .top-navbar { background-color: #212529 !important; border-bottom: 1px solid #373b3e; }
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
        <nav class="navbar top-navbar px-4 py-3 d-flex justify-content-between align-items-center">
            <h5 class="m-0 fw-bold">@yield('page-title')</h5>
            <div class="d-flex align-items-center gap-3">
                <div class="btn-group btn-group-sm rounded-pill border overflow-hidden p-0" style="background: rgba(0,0,0,0.05);">
                    <button class="btn btn-link theme-toggle p-2 text-decoration-none border-0" data-theme="light" title="Mode Lumineux">
                        <i class="bi bi-sun-fill"></i>
                    </button>
                    <button class="btn btn-link theme-toggle p-2 text-decoration-none border-0" data-theme="dark" title="Mode Sombre">
                        <i class="bi bi-moon-stars-fill"></i>
                    </button>
                    <button class="btn btn-link theme-toggle p-2 text-decoration-none border-0" data-theme="auto" title="Mode Système">
                        <i class="bi bi-display"></i>
                    </button>
                </div>
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
<script>
    const themeToggles = document.querySelectorAll('.theme-toggle');
    
    const showActiveTheme = (theme) => {
        themeToggles.forEach(btn => {
            btn.classList.remove('active');
            if (btn.getAttribute('data-theme') === theme) {
                btn.classList.add('active');
            }
        });
    };

    const initialTheme = localStorage.getItem('theme') || 'auto';
    showActiveTheme(initialTheme);

    themeToggles.forEach(btn => {
        btn.addEventListener('click', () => {
            const theme = btn.getAttribute('data-theme');
            localStorage.setItem('theme', theme);
            
            const getPreferredTheme = () => {
                if (theme !== 'auto') return theme;
                return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            };
            
            document.documentElement.setAttribute('data-bs-theme', getPreferredTheme());
            showActiveTheme(theme);
        });
    });
</script>
@yield('scripts')
</body>
</html>
