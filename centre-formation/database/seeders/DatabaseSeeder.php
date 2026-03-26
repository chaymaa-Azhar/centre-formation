<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Formateur;
use App\Models\Formation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Création de l'administrateur par défaut
        User::updateOrCreate(
        ['email' => 'admin@centre.ma'],
        [
            'name' => 'Administrateur',
            'password' => Hash::make('admin123'),
        ]
        );

        // Création de 3 formateurs par défaut
        $hamid = Formateur::updateOrCreate(
            ['email' => 'hamid@centre.ma'],
            [
                'nom' => 'Alami',
                'prenom' => 'Hamid',
                'specialite' => 'Développement Web',
                'telephone' => '0601020304',
                'password' => Hash::make('123456'),
            ]
        );

        $hajar = Formateur::updateOrCreate(
            ['email' => 'hajar@centre.ma'],
            [
                'nom' => 'Azhar',
                'prenom' => 'Hajar',
                'specialite' => 'Marketing Digital',
                'telephone' => '0605060708',
                'password' => Hash::make('123456'),
            ]
        );

        $houda = Formateur::updateOrCreate(
            ['email' => 'houda@centre.ma'],
            [
                'nom' => 'Imssedak',
                'prenom' => 'Houda',
                'specialite' => 'Design Graphique',
                'telephone' => '0609101112',
                'password' => Hash::make('123456'),
            ]
        );

        // Création de formations de test réparties entre les formateurs
        Formation::updateOrCreate(
            ['titre' => 'Développement Web Fullstack'],
            [
                'description' => 'Maîtrisez HTML, CSS, JS, PHP et Laravel.',
                'duree' => '4 mois',
                'prix' => 2500,
                'places' => 15,
                'formateur_id' => $hamid->id,
            ]
        );

        Formation::updateOrCreate(
            ['titre' => 'Design Graphique'],
            [
                'description' => 'Apprenez Photoshop, Illustrator et Figma.',
                'duree' => '3 mois',
                'prix' => 1800,
                'places' => 10,
                'formateur_id' => $houda->id,
            ]
        );

        Formation::updateOrCreate(
            ['titre' => 'Marketing Digital'],
            [
                'description' => 'SEO, Google Ads et réseaux sociaux.',
                'duree' => '2 mois',
                'prix' => 1500,
                'places' => 20,
                'formateur_id' => $hajar->id,
            ]
        );
    }
}
