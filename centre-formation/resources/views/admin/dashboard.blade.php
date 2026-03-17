@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold m-0">Dashboard Statistiques</h3>
</div>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white shadow-sm border-0">
            <div class="card-body py-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50 mb-1">Total Inscriptions</h6>
                        <h2 class="fw-bold mb-0">{{ $totalInscriptions }}</h2>
                    </div>
                    <i class="bi bi-card-checklist fs-1 text-white-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white shadow-sm border-0">
            <div class="card-body py-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50 mb-1">Total Étudiants</h6>
                        <h2 class="fw-bold mb-0">{{ $totalEtudiants }}</h2>
                    </div>
                    <i class="bi bi-people fs-1 text-white-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white shadow-sm border-0" style="background-color: #00838f;">
            <div class="card-body py-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50 mb-1">Total Formateurs</h6>
                        <h2 class="fw-bold mb-0">{{ $totalFormateurs }}</h2>
                    </div>
                    <i class="bi bi-person-badge fs-1 text-white-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white shadow-sm border-0" style="background-color: #5c6bc0;">
            <div class="card-body py-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50 mb-1">Total Formations</h6>
                        <h2 class="fw-bold mb-0">{{ $totalFormations }}</h2>
                    </div>
                    <i class="bi bi-book fs-1 text-white-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Graphiques -->
<div class="row mb-4">
    <div class="col-md-6 mb-4 mb-md-0">
        <div class="card shadow-sm border-0 h-100 bg-light">
            <div class="card-header bg-secondary text-white fw-bold py-3"><i class="bi bi-pie-chart me-2"></i>Répartition des étudiants par formation</div>
            <div class="card-body d-flex justify-content-center">
                <div style="height: 220px; width: 100%; display: flex; justify-content: center;">
                    <canvas id="etudiantsChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm border-0 h-100 bg-light">
            <div class="card-header bg-secondary text-white fw-bold py-3"><i class="bi bi-graph-up me-2"></i>Revenus financiers par mois</div>
            <div class="card-body d-flex justify-content-center">
                <div style="height: 220px; width: 100%; display: flex; justify-content: center;">
                    <canvas id="revenusChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm border-0 bg-light">
            <div class="card-header bg-secondary text-white fw-bold py-3"><i class="bi bi-clock-history me-2"></i>Inscriptions Récentes</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-secondary">
                            <tr>
                                <th>Étudiant</th>
                                <th>Formation</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($inscriptionsRecentes as $ins)
                            <tr>
                                <td>{{ $ins->etudiant->nom }} {{ $ins->etudiant->prenom }}</td>
                                <td>{{ $ins->formation->titre }}</td>
                                <td>
                                    <span class="badge bg-{{ $ins->statut == 'Validé' ? 'success' : ($ins->statut == 'Refusé' ? 'danger' : 'warning text-dark') }}">
                                        {{ $ins->statut }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-center text-muted py-3">Aucune inscription récente</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm border-0 bg-light">
            <div class="card-header bg-secondary text-white fw-bold py-3"><i class="bi bi-wallet2 me-2"></i>Paiements Récents</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-secondary">
                            <tr>
                                <th>Étudiant</th>
                                <th>Montant</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($paiementsRecents as $paiement)
                            <tr>
                                <td>{{ $paiement->etudiant->nom }}</td>
                                <td class="fw-bold">{{ $paiement->montant }} MAD</td>
                                <td>
                                    <span class="badge bg-{{ $paiement->statut == 'Payé' ? 'success' : 'warning text-dark' }}">
                                        {{ $paiement->statut }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-center text-muted py-3">Aucun paiement récent</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<!-- Script Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // --- Graphique Revenus par mois (Line Chart) ---
        const ctxRevenus = document.getElementById('revenusChart').getContext('2d');
        const revenusData = @json($revenusParMois);
        
        // Formatage des dates "Y-m" en clair (Optionnel)
        const mois = revenusData.map(item => item.mois);
        const totaux = revenusData.map(item => item.total);

        new Chart(ctxRevenus, {
            type: 'line',
            data: {
                labels: mois.length > 0 ? mois : ['Aucune donnée'],
                datasets: [{
                    label: 'Revenus validés (MAD)',
                    data: totaux.length > 0 ? totaux : [0],
                    borderColor: '#0b5ed7', // Plus foncé
                    backgroundColor: 'rgba(11, 94, 215, 0.3)', // Plus opaque/foncé
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3,
                    pointBackgroundColor: '#0a58ca',
                    pointRadius: 4
                }]
            },
            options: { 
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });

        // --- Graphique Étudiants par formation (Doughnut Chart) ---
        const ctxEtudiants = document.getElementById('etudiantsChart').getContext('2d');
        const etudiantsData = @json($etudiantsParFormation);
        
        const formations = etudiantsData.map(item => item.titre);
        const counts = etudiantsData.map(item => item.inscriptions_count);

        new Chart(ctxEtudiants, {
            type: 'doughnut',
            data: {
                labels: formations.length > 0 ? formations : ['Aucune formation'],
                datasets: [{
                    label: 'Nombre d\'étudiants inscrits',
                    data: counts.length > 0 ? counts : [0],
                    backgroundColor: [
                        '#d84315', // deep orange (changed from green)
                        '#087f94', // info dark
                        '#cc9a06', // warning dark
                        '#b02a37', // danger dark
                        '#0a58ca', // primary dark
                        '#565e64', // secondary dark
                        '#520dc2', // indigo dark
                        '#ac296a'  // pink dark
                    ],
                    borderWidth: 1
                }]
            },
            options: { 
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right'
                    }
                }
            }
        });
    });
</script>
@endsection
