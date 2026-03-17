@extends('layouts.formateur')

@section('title', 'Mon Planning - Formateur')
@section('page-title', 'Mon Planning')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-calendar-week me-2 text-primary"></i>Mes Sessions de Cours</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Formation / Matière</th>
                                <th>Période</th>
                                <th>Jours & Horaires</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sessions as $session)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold">{{ $session->formation->titre }}</div>
                                        <div class="text-muted small">{{ $session->matiere }}</div>
                                    </td>
                                    <td>
                                        <div class="small">
                                            <i class="bi bi-calendar-event me-1"></i>
                                            Du {{ \Carbon\Carbon::parse($session->date_debut)->format('d/m/Y') }}
                                        </div>
                                        <div class="small">
                                            <i class="bi bi-calendar-check me-1"></i>
                                            Au {{ \Carbon\Carbon::parse($session->date_fin)->format('d/m/Y') }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="badge bg-light text-dark border p-2 mb-1">
                                            <i class="bi bi-clock me-1"></i> {{ substr($session->heure_debut, 0, 5) }} - {{ substr($session->heure_fin, 0, 5) }}
                                        </div>
                                        <div>
                                            @if($session->jours && is_array($session->jours))
                                                @foreach($session->jours as $jour)
                                                    <span class="badge bg-secondary rounded-pill me-1 small fw-normal">{{ $jour }}</span>
                                                @endforeach
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                            $now = \Carbon\Carbon::now();
                                            $start = \Carbon\Carbon::parse($session->date_debut);
                                            $end = \Carbon\Carbon::parse($session->date_fin);
                                        @endphp

                                        @if($now->between($start, $end))
                                            <span class="badge bg-success">En cours</span>
                                        @elseif($now->lt($start))
                                            <span class="badge bg-info">À venir</span>
                                        @else
                                            <span class="badge bg-secondary">Terminée</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5">
                                        <i class="bi bi-calendar-x text-muted fs-1 d-block mb-3"></i>
                                        <p class="text-muted">Aucune session n'est planifiée pour le moment.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
