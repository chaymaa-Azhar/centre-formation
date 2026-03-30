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
        $prof1 = Formateur::updateOrCreate(
            ['email' => 'prof1@centre.ma'],
            [
                'nom' => 'Test',
                'prenom' => 'Prof 1',
                'specialite' => 'Développement Web',
                'telephone' => '0601020304',
                'password' => Hash::make('123456'),
            ]
        );

        $prof2 = Formateur::updateOrCreate(
            ['email' => 'prof2@centre.ma'],
            [
                'nom' => 'Test',
                'prenom' => 'Prof 2',
                'specialite' => 'Marketing Digital',
                'telephone' => '0605060708',
                'password' => Hash::make('123456'),
            ]
        );

        $prof3 = Formateur::updateOrCreate(
            ['email' => 'prof3@centre.ma'],
            [
                'nom' => 'Test',
                'prenom' => 'Prof 3',
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
                'formateur_id' => $prof1->id,
            ]
        );

        Formation::updateOrCreate(
            ['titre' => 'Design Graphique'],
            [
                'description' => 'Apprenez Photoshop, Illustrator et Figma.',
                'duree' => '3 mois',
                'prix' => 1800,
                'places' => 10,
                'formateur_id' => $prof3->id,
            ]
        );

        Formation::updateOrCreate(
            ['titre' => 'Marketing Digital'],
            [
                'description' => 'SEO, Google Ads et réseaux sociaux.',
                'duree' => '2 mois',
                'prix' => 1500,
                'places' => 20,
                'formateur_id' => $prof2->id,
            ]
        );
    }
}
