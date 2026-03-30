<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Étudiants - Centre Formation</title>
    <style>
        body { font-family: Helvetica, Arial, sans-serif; color: #333; margin: 0; padding: 20px; font-size: 13px; }
        .header { text-align: center; border-bottom: 2px solid #087f94; padding-bottom: 15px; margin-bottom: 25px; }
        .header h1 { color: #087f94; margin: 0; font-size: 22px; text-transform: uppercase; }
        .header p { margin: 5px 0 0 0; color: #666; font-size: 12px; }
        .stat-bar { display: flex; gap: 20px; background: #f8f9fa; border: 1px solid #e9ecef; padding: 10px 15px; margin-bottom: 20px; border-radius: 4px; }
        .stat-bar div { font-size: 13px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #dee2e6; padding: 9px 11px; font-size: 12px; }
        th { background-color: #087f94; color: white; font-weight: bold; text-transform: uppercase; font-size: 11px; }
        tr:nth-child(even) td { background-color: #f8f9fa; }
        .footer { margin-top: 30px; text-align: center; font-size: 10px; color: #999; border-top: 1px solid #eee; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Centre Formation</h1>
        <p>Liste Officielle des Étudiants Inscrits &mdash; Édité le {{ date('d/m/Y') }}</p>
    </div>

    <div class="stat-bar">
        <div><strong>Total étudiants :</strong> {{ $etudiants->count() }}</div>
        <div><strong>Date d'export :</strong> {{ date('d/m/Y à H:i') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nom & Prénom</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Formation(s)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($etudiants as $i => $etudiant)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td><strong>{{ mb_strtoupper($etudiant->nom) }}</strong> {{ ucfirst($etudiant->prenom) }}</td>
                <td>{{ $etudiant->email }}</td>
                <td>{{ $etudiant->telephone }}</td>
                <td>
                    @foreach($etudiant->inscriptions as $ins)
                        {{ $ins->formation->titre ?? 'N/A' }}@if(!$loop->last), @endif
                    @endforeach
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Document généré automatiquement le {{ date('d/m/Y à H:i') }}. Réservé à l'usage interne du Centre de Formation.
    </div>
</body>
</html>
