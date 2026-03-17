<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Formateur;

class Formation extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'description',
        'duree',
        'prix',
        'places',
        'formateur_id'
    ];

    // relation entre formateur et formation
    public function formateur()
    {
        return $this->belongsTo(Formateur::class);
    }

    //relation entre formation et formateur
    public function etudiants()
{
    return $this->hasMany(Etudiant::class);
}

    public function inscriptions()
    {
        return $this->hasMany(Inscription::class);
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }

    public function sessions()
    {
        return $this->hasMany(SessionCours::class);
    }

    public function notes()
    {
        return $this->hasMany(Note::class);
    }

}