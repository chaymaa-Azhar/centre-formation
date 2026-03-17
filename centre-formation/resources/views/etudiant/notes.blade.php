@extends('layouts.etudiant')

@section('title', 'Espace Étudiant - Mes Notes')
@section('page-title', 'Mes Notes')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold m-0"><i class="bi bi-journal-check me-2 text-primary"></i>Vos évaluations</h3>
    </div>

    @if($notes->isEmpty())
        <div class="alert alert-info shadow-sm border-0 text-center py-5">
            <i class="bi bi-info-circle-fill fs-2 mb-3 d-block"></i>
            <p class="mb-0">Vous n'avez pas encore de notes enregistrées.</p>
        </div>
    @else
        <div class="row">
            <div class="col-md-8">
                <div class="card shadow-sm border-0 bg-light">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 align-middle">
                                <thead class="table-secondary">
                                    <tr>
                                        <th>Formation</th>
                                        <th>Matière / Éval</th>
                                        <th>Formateur</th>
                                        <th>Note / 20</th>
                                        <th>Appréciation</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($notes as $note)
                                        <tr>
                                            <td class="fw-semibold">{{ $note->formation->titre }}</td>
                                            <td>Examen / Participation</td>
                                            <td>{{ $note->formateur->nom ?? 'N/A' }}</td>
                                            <td>
                                                @php
                                                    $color = 'text-dark';
                                                    if($note->valeur >= 16) $color = 'text-success';
                                                    elseif($note->valeur >= 10) $color = 'text-primary';
                                                    else $color = 'text-danger';
                                                @endphp
                                                <span class="fs-4 fw-bold {{ $color }}">{{ $note->valeur }}</span>
                                            </td>
                                            <td>
                                                @if($note->valeur >= 10)
                                                    <span class="badge bg-success-subtle text-success border border-success">Validé</span>
                                                @else
                                                    <span class="badge bg-danger-subtle text-danger border border-danger">Non validé</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-white p-3 h-100">
                    <h5 class="fw-bold mb-3">Statistiques</h5>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Moyenne Générale :</span>
                        <span class="fw-bold text-primary">{{ number_format($notes->avg('valeur'), 2) }} / 20</span>
                    </div>

                    <hr>
                    <p class="small text-muted">Ces notes sont fournies à titre informatif par vos formateurs respectifs.</p>
                </div>
            </div>
        </div>
    @endif
@endsection
