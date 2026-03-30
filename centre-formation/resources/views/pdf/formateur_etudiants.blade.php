<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes Étudiants - {{ $formateur->prenom }} {{ mb_strtoupper($formateur->nom) }}</title>
    <style>
        body { font-family: Helvetica, Arial, sans-serif; color: #333; margin: 0; padding: 20px; font-size: 13px; }
        .header { text-align: center; border-bottom: 2px solid #2c3e50; padding-bottom: 15px; margin-bottom: 25px; }
        .header h1 { color: #2c3e50; margin: 0; font-size: 22px; text-transform: uppercase; }
        .header p { margin: 5px 0 0 0; color: #666; font-size: 12px; }
        .formateur-info { background: #f8f9fa; border-left: 4px solid #2c3e50; padding: 10px 15px; margin-bottom: 20px; }
        .formateur-info p { margin: 3px 0; font-size: 13px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #dee2e6; padding: 9px 11px; font-size: 12px; }
        th { background-color: #2c3e50; color: white; font-weight: bold; text-transform: uppercase; font-size: 11px; }
        tr:nth-child(even) td { background-color: #f8f9fa; }
        .footer { margin-top: 30px; text-align: center; font-size: 10px; color: #999; border-top: 1px solid #eee; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Centre Formation</h1>
        <p>Liste de Classe &mdash; Étudiants du Formateur</p>
    </div>

    <div class="formateur-info">
        <p><strong>Formateur :</strong> {{ ucfirst($formateur->prenom) }} {{ mb_strtoupper($formateur->nom) }}</p>
        <p><strong>Spécialité :</strong> {{ $formateur->specialite }}</p>
        <p><strong>Date d'édition :</strong> {{ date('d/m/Y') }}</p>
    </div>

    @if($etudiants->isEmpty())
        <p style="text-align:center; color:#666; font-style:italic;">Aucun étudiant assigné pour le moment.</p>
    @else
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nom & Prénom</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Formation</th>
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
    @endif

    <div class="footer">
        Document généré le {{ date('d/m/Y à H:i') }}. Total : {{ $etudiants->count() }} étudiant(s).
    </div>
</body>
</html>
