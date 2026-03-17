@extends('layouts.etudiant')

@section('title', 'Espace Étudiant - Mes Paiements')
@section('page-title', 'Mes Paiements')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold m-0"><i class="bi bi-wallet2 me-2 text-primary"></i>Historique des paiements</h3>
    </div>

    @if($paiements->isEmpty())
        <div class="alert alert-info shadow-sm border-0 text-center py-5">
            <i class="bi bi-wallet2 fs-2 mb-3 d-block"></i>
            <p class="mb-0">Aucun historique de paiement pour le moment.</p>
        </div>
    @else
        <div class="card shadow-sm border-0 bg-light">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-secondary">
                            <tr>
                                <th>Date</th>
                                <th>Formation</th>
                                <th>Montant</th>
                                <th>Mode de paiement</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($paiements as $paiement)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($paiement->date_paiement)->format('d/m/Y') }}</td>
                                    <td class="fw-semibold">{{ $paiement->formation->titre }}</td>
                                    <td class="fw-bold text-dark">{{ number_format($paiement->montant, 2) }} MAD</td>
                                    <td>
                                        <span class="badge bg-light text-dark border">{{ $paiement->mode_paiement }}</span>
                                    </td>
                                    <td>
                                        @if($paiement->statut == 'Payé')
                                            <span class="badge bg-success-subtle text-success border border-success">
                                                <i class="bi bi-check-circle me-1"></i> {{ $paiement->statut }}
                                            </span>
                                        @else
                                            <span class="badge bg-warning-subtle text-warning border border-warning text-dark">
                                                <i class="bi bi-clock-history me-1"></i> {{ $paiement->statut }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="mt-4 p-3 bg-white shadow-sm border-0 rounded">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0 fw-bold">Total réglé à ce jour :</p>
                </div>
                <div class="col-md-6 text-end">
                    <h4 class="mb-0 fw-bold text-success">{{ number_format($paiements->where('statut', 'Payé')->sum('montant'), 2) }} MAD</h4>
                </div>
            </div>
        </div>
    @endif
@endsection
