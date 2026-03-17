<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SessionCours extends Model
{
    protected $table = 'session_cours';

    protected $fillable = [
        'formation_id',
        'formateur_id',
        'date_debut',
        'date_fin',
        'heure_debut',
        'heure_fin',
        'jours',
        'matiere',
    ];

    protected $casts = [
        'jours' => 'array',
    ];

    public function formation()
    {
        return $this->belongsTo(Formation::class);
    }

    public function formateur()
    {
        return $this->belongsTo(Formateur::class);
    }
}
