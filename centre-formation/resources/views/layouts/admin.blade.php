<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Centre de Formation - Admin</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
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
        [data-bs-theme="light"] body {
            background-color: #f8f9fa;
        }

        [data-bs-theme="light"] .main-content .table thead th {
            background-color: #bebebeff !important;
        }

        [data-bs-theme="dark"] body {
            background-color: #121212;
        }

        .sidebar {
            background-color: #212529;
            min-height: 100vh;
            position: fixed;
            width: 250px;
            transition: background-color 0.3s;
        }

        .theme-toggle.active {
            background-color: rgba(255, 255, 255, 0.2) !important;
            border-color: #fff !important;
        }

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
        [data-bs-theme="dark"] .main-content .table-light {
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
        [data-bs-theme="dark"] .main-content .table-secondary td {
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

        .main-content {
            margin-left: 250px;
            padding: 20px;
            width: calc(100% - 250px);
        }

        .nav-link {
            color: rgba(255, 255, 255, .75);
            padding: 12px 20px;
        }

        .nav-link:hover,
        .nav-link.active {
            color: #fff;
            background-color: rgba(255, 255, 255, .1);
        }

        .new-row {
            animation: highlight-fade 7s ease-out forwards;
        }

        @keyframes highlight-fade {
            0% {
                background-color: #fff3cd;
            }

            /* Jaune clair très visible */
            100% {
                background-color: transparent;
            }
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
                    <img src="{{ asset('logo.png') }}" alt="Logo"
                        style="height: 40px; width: auto; object-fit: contain;"
                        onerror="this.outerHTML='<i class=\'bi bi-mortarboard-fill text-info\' style=\'font-size: 2.2rem;\'></i>'">

                    <!-- Le texte normal sur une seule ligne -->
                    <h5 class="mb-0 fw-bolder text-white text-nowrap"
                        style="letter-spacing: 0.5px; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
                        Centre Formation</h5>
                </div>

                <span class="badge border border-secondary text-light rounded-pill px-3 mt-1"
                    style="background-color: rgba(255,255,255,0.05); font-size: 0.70rem; letter-spacing: 1px;">
                    ESPACE ADMIN
                </span>
            </div>
            <ul class="nav flex-column py-3">
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}"
                        class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2 me-2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.inscriptions.index') }}"
                        class="nav-link {{ request()->routeIs('admin.inscriptions.*') ? 'active' : '' }}">
                        <i class="bi bi-card-checklist me-2"></i> Inscriptions
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.etudiants.index') }}"
                        class="nav-link {{ request()->routeIs('admin.etudiants.*') ? 'active' : '' }}">
                        <i class="bi bi-people me-2"></i> Étudiants
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.formateurs.index') }}"
                        class="nav-link {{ request()->routeIs('admin.formateurs.*') ? 'active' : '' }}">
                        <i class="bi bi-person-badge me-2"></i> Formateurs
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.formations.index') }}"
                        class="nav-link {{ request()->routeIs('admin.formations.*') ? 'active' : '' }}">
                        <i class="bi bi-book me-2"></i> Formations
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.paiements.index') }}"
                        class="nav-link {{ request()->routeIs('admin.paiements.*') ? 'active' : '' }}">
                        <i class="bi bi-wallet2 me-2"></i> Paiements
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.sessions.index') }}"
                        class="nav-link {{ request()->routeIs('admin.sessions.*') ? 'active' : '' }}">
                        <i class="bi bi-calendar-event me-2"></i> Sessions
                    </a>
                </li>
            </ul>

            <div class="px-3 mt-auto mb-4 w-100" style="position: absolute; bottom: 0;">
                <div class="d-flex gap-2 mb-3">
                    <button class="btn btn-sm btn-outline-light flex-grow-1 theme-toggle" data-theme="light"
                        title="Mode Lumineux">
                        <i class="bi bi-sun-fill"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-light flex-grow-1 theme-toggle" data-theme="dark"
                        title="Mode Sombre">
                        <i class="bi bi-moon-stars-fill"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-light flex-grow-1 theme-toggle" data-theme="auto"
                        title="Mode Système">
                        <i class="bi bi-display"></i>
                    </button>
                </div>
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