<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Relevé de Notes - {{ mb_strtoupper($etudiant->nom) }} {{ ucfirst($etudiant->prenom) }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #087f94;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #087f94;
            margin: 0;
            font-size: 26px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .header p {
            margin: 5px 0 0 0;
            color: #666;
            font-size: 14px;
        }
        .student-info {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 30px;
        }
        .student-info p {
            margin: 5px 0;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th, td {
            border: 1px solid #dee2e6;
            padding: 12px;
            text-align: left;
            font-size: 14px;
        }
        th {
            background-color: #f1f3f5;
            color: #495057;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 12px;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .note-good { color: #198754; font-weight: bold; }
        .note-ok { color: #0d6efd; font-weight: bold; }
        .note-bad { color: #dc3545; font-weight: bold; }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 11px;
            color: #6c757d;
            border-top: 1px solid #dee2e6;
            padding-top: 20px;
        }
        .signature {
            text-align: right;
            margin-top: 40px;
            margin-right: 20px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Centre Formation</h1>
        <p>Relevé de Notes Officiel</p>
    </div>

    <div class="student-info">
        <p><strong>Étudiant :</strong> {{ mb_strtoupper($etudiant->nom) }} {{ ucfirst($etudiant->prenom) }}</p>
        <p><strong>Email :</strong> {{ $etudiant->email }}</p>
        <p><strong>Date d'édition :</strong> {{ date('d/m/Y') }}</p>
    </div>

    @if($notes->isEmpty())
        <p style="text-align: center; font-style: italic; color: #666;">Aucune note enregistrée pour le moment.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Formation</th>
                    <th>Formateur</th>
                    <th class="text-center">Note / 20</th>
                    <th class="text-center">Statut</th>
                </tr>
            </thead>
            <tbody>
                @foreach($notes as $note)
                    <tr>
                        <td>{{ $note->formation->titre }}</td>
                        <td>{{ $note->formateur->prenom ?? 'N/A' }} {{ mb_strtoupper($note->formateur->nom ?? '') }}</td>
                        <td class="text-center">
                            @php
                                $color = 'note-bad';
                                if($note->valeur >= 16) $color = 'note-good';
                                elseif($note->valeur >= 10) $color = 'note-ok';
                            @endphp
                            <span class="{{ $color }}">{{ number_format($note->valeur, 2) }}</span>
                        </td>
                        <td class="text-center">
                            @if($note->valeur >= 10)
                                <span style="color: #198754; font-weight: bold;">Validé</span>
                            @else
                                <span style="color: #dc3545; font-weight: bold;">Non validé</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="text-right" style="font-size: 16px; margin-right: 15px; background-color: #f8f9fa; padding: 15px; border: 1px solid #dee2e6;">
            <strong>Moyenne Générale : 
                <span style="color: #087f94; font-size: 18px; margin-left: 10px;">{{ number_format($notes->avg('valeur'), 2) }} / 20</span>
            </strong>
        </div>
    @endif

    <div class="signature">
        <p><strong>La Direction Pédagogique</strong><br>
        <em style="font-size: 12px; color: #666;">Cachet et signature</em></p>
    </div>

    <div class="footer">
        Document généré informatiquement le {{ date('d/m/Y') }} à {{ date('H:i') }}. Toute falsification est passible de poursuites.
    </div>

</body>
</html>
