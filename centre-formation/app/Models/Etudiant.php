<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Etudiant extends Authenticatable
{
    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'telephone',
        'password',
        'formation_id'
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    // relation avec Formation
    public function formation()
    {
        return $this->belongsTo(Formation::class);
    }

    public function inscriptions()
    {
        return $this->hasMany(Inscription::class);
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }

    public function notes()
    {
        return $this->hasMany(Note::class);
    }
}