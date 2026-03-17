<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Formation;

class Formateur extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'telephone',
        'specialite',
        'experience',
        'password',
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

    //relation entre formation et formateur
    public function formations()
    {
        return $this->hasMany(Formation::class);
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