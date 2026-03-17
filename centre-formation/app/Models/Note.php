<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $fillable = [
        'etudiant_id',
        'formation_id',
        'formateur_id',
        'valeur',
    ];

    public function etudiant()
    {
        return $this->belongsTo(Etudiant::class);
    }

    public function formation()
    {
        return $this->belongsTo(Formation::class);
    }

    public function formateur()
    {
        return $this->belongsTo(Formateur::class);
    }
}
