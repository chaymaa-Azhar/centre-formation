@extends('layouts.etudiant')

@section('title', 'Espace Étudiant - Mon Planning')
@section('page-title', 'Mon Planning')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold m-0"><i class="bi bi-calendar3 me-2 text-primary"></i>Calendrier des sessions</h3>
    </div>

    @if($sessions->isEmpty())
        <div class="alert alert-info shadow-sm border-0 text-center py-5">
            <i class="bi bi-calendar-x fs-2 mb-3 d-block"></i>
            <p class="mb-0">Aucune session de cours n'est programmée pour vos formations validées.</p>
            <small class="text-muted">Si vous venez de vous inscrire, attendez que l'administrateur valide votre inscription.</small>
        </div>
    @else
        <div class="card shadow-sm border-0 bg-light">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-secondary">
                            <tr>
                                <th>Formation</th>
                                <th>Date</th>
                                <th>Horaires</th>
                                <th>Matière</th>
                                <th>Formateur</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sessions as $session)
                                @php
                                    $isPast = \Carbon\Carbon::parse($session->date_fin)->isPast();
                                @endphp
                                <tr class="{{ $isPast ? 'text-muted' : '' }}">
                                    <td>
                                        <div class="fw-bold text-primary">{{ $session->formation->titre }}</div>
                                    </td>
                                    <td>
                                        @if($session->date_debut == $session->date_fin)
                                            <div class="fw-bold">{{ \Carbon\Carbon::parse($session->date_debut)->format('d/m/Y') }}</div>
                                        @else
                                            <div class="fw-bold">
                                                Du {{ \Carbon\Carbon::parse($session->date_debut)->format('d/m/Y') }} <br>
                                                Au {{ \Carbon\Carbon::parse($session->date_fin)->format('d/m/Y') }}
                                            </div>
                                        @endif
                                        <div class="mt-1">
                                            @if($session->jours)
                                                @foreach($session->jours as $jour)
                                                    <span class="badge bg-light text-primary border-primary border p-1" style="font-size: 0.65rem;">{{ $jour }}</span>
                                                @endforeach
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary rounded-pill px-3 py-2">
                                            <i class="bi bi-clock me-1"></i> {{ \Carbon\Carbon::parse($session->heure_debut)->format('H:i') }} - {{ \Carbon\Carbon::parse($session->heure_fin)->format('H:i') }}
                                        </span>
                                    </td>
                                    <td class="fw-semibold">{{ $session->matiere }}</td>
                                    <td>
                                        @if($session->formateur)
                                            <i class="bi bi-person me-1 text-secondary"></i> {{ $session->formateur->nom }} {{ $session->formateur->prenom }}
                                        @else
                                            <span class="text-muted fst-italic">Non assigné</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
@endsection
