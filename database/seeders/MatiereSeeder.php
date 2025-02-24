<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MatiereSeeder extends Seeder
{
    public function run()
    {
        $matieres = [
            // === Matières scientifiques ===
            ['nom' => 'Mathématiques', 'description' => 'Étude des nombres, des équations, et des probabilités.'],
            ['nom' => 'Physique', 'description' => 'Étude des lois de la nature et des phénomènes physiques.'],
            ['nom' => 'Chimie organique', 'description' => 'Étude des composés carbonés et leurs réactions.'],
            ['nom' => 'Chimie analytique', 'description' => 'Analyse des substances chimiques et leurs propriétés.'],
            ['nom' => 'SVT', 'description' => 'Étude de la biologie et de la géologie.'],
            ['nom' => 'Astrophysique', 'description' => 'Étude des étoiles, des planètes et des galaxies.'],
            ['nom' => 'Statistiques', 'description' => 'Analyse et interprétation des données quantitatives.'],
            ['nom' => 'Algèbre', 'description' => 'Étude des structures mathématiques et des équations.'],
            ['nom' => 'Géométrie', 'description' => 'Étude des formes et des espaces.'],
            ['nom' => 'Mécanique des fluides', 'description' => 'Étude des fluides et de leur mouvement.'],
            ['nom' => 'Informatique', 'description' => 'Programmation, algorithmes et systèmes informatiques.'],
            ['nom' => 'Biotechnologie', 'description' => 'Application des sciences biologiques à la technologie.'],
            ['nom' => 'Génétique', 'description' => 'Étude de l’hérédité et des gènes.'],

            // === Matières littéraires ===
            ['nom' => 'Langue française', 'description' => 'Analyse grammaticale et linguistique.'],
            ['nom' => 'Littérature mondiale', 'description' => 'Analyse des grandes œuvres littéraires mondiales.'],
            ['nom' => 'Histoire ancienne', 'description' => 'Étude des civilisations anciennes comme Rome et l’Égypte.'],
            ['nom' => 'Histoire contemporaine', 'description' => 'Étude des événements récents et de leur impact.'],
            ['nom' => 'Philosophie morale', 'description' => 'Réflexion sur les concepts de bien et de mal.'],
            ['nom' => 'Poésie', 'description' => 'Analyse et écriture de poèmes.'],

            // === Langues ===
            ['nom' => 'Anglais des affaires', 'description' => 'Langue anglaise orientée vers les situations professionnelles.'],
            ['nom' => 'Espagnol conversationnel', 'description' => 'Amélioration de la pratique orale en espagnol.'],
            ['nom' => 'Japonais', 'description' => 'Étude de la langue et de la culture japonaise.'],
            ['nom' => 'Latin', 'description' => 'Langue ancienne utilisée dans les textes classiques.'],

            // === Matières technologiques ===
            ['nom' => 'Programmation Python', 'description' => 'Introduction à la programmation avec Python.'],
            ['nom' => 'Réseaux informatiques', 'description' => 'Concepts des réseaux LAN, WAN et sécurité.'],
            ['nom' => 'Développement mobile', 'description' => 'Création d’applications pour iOS et Android.'],
            ['nom' => 'IA et Machine Learning', 'description' => 'Introduction à l’intelligence artificielle et ses algorithmes.'],
            ['nom' => 'Cybersécurité', 'description' => 'Protection des systèmes contre les cyberattaques.'],
            ['nom' => 'Bases de données', 'description' => 'Gestion des données avec SQL et NoSQL.'],

            // === Matières artistiques ===
            ['nom' => 'Peinture', 'description' => 'Apprentissage des techniques de peinture.'],
            ['nom' => 'Sculpture', 'description' => 'Création d’œuvres en trois dimensions.'],
            ['nom' => 'Design graphique', 'description' => 'Création visuelle pour supports numériques et imprimés.'],
            ['nom' => 'Photographie', 'description' => 'Techniques et histoire de la photographie.'],

            // === Matières sportives ===
            ['nom' => 'Athlétisme', 'description' => 'Pratique des sports individuels comme la course.'],
            ['nom' => 'Natation', 'description' => 'Entraînement et techniques de nage.'],
            ['nom' => 'Sports collectifs', 'description' => 'Basketball, football, handball, et autres.'],

            // === Parcours professionnels ===
            ['nom' => 'Économie', 'description' => 'Bases des concepts économiques.'],
            ['nom' => 'Management', 'description' => 'Introduction à la gestion des entreprises.'],
            ['nom' => 'Logistique', 'description' => 'Gestion de la chaîne d’approvisionnement.'],
            ['nom' => 'Droit des affaires', 'description' => 'Introduction au droit commercial.'],
            ['nom' => 'Hôtellerie et tourisme', 'description' => 'Organisation et gestion des services touristiques.'],
            ['nom' => 'Médical', 'description' => 'Terminologie médicale et principes de base en santé.'],

            // === Autres matières ===
            ['nom' => 'Écologie et environnement', 'description' => 'Étude des enjeux environnementaux.'],
            ['nom' => 'Nutrition', 'description' => 'Principes de base de la santé alimentaire.'],
            ['nom' => 'Éducation morale', 'description' => 'Valeurs et principes de citoyenneté.'],
        ];

        DB::table('matieres')->insertOrIgnore($matieres);
    }
}