<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inscription extends Model
{
    protected $fillable = [
        'etudiant_id',
        'formation_id',
        'statut',
        'source',
    ];

    public function etudiant()
    {
        return $this->belongsTo(Etudiant::class);
    }

    public function formation()
    {
        return $this->belongsTo(Formation::class);
    }

    public function paiement()
    {
        return $this->hasOne(Paiement::class, 'formation_id', 'formation_id')
                    ->where('etudiant_id', $this->etudiant_id);
    }
}
