@extends('layouts.templateProfesseur')
@section('content')
<style>

    .dashboard-card {
        background-color: #ffffff;
        padding: 40px;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        margin-top: 30px;
        transition: transform 0.3s ease, box-shadow 0.3s ease; 
    }

    .dashboard-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 6px 30px rgba(0, 0, 0, 0.15); 
    }

    .dashboard-title h1 {
        font-family: 'Exo 2', serif;
        font-size: 2.8rem;
        font-weight: 700;
        color: #080c18;
        margin-bottom: 20px;
        text-align: center;
        letter-spacing: 1px;
        line-height: 1.3;
    }

    .dashboard-text {
        font-size: 1.2rem;
        color: #5d6d7e;
        line-height: 1.8;
        margin-bottom: 25px;
        text-align: justify;
        font-weight: 400;
    }

    .image-container {
        margin-top: 30px;
        text-align: center;
    }

    .image-container img {
        width: 100%;
        max-width: 800px;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        display: block;
        margin: 0 auto;
        height: auto;
        transition: transform 0.3s ease;
    }

    .image-container img:hover {
        transform: scale(1.05);
    }

    @media (max-width: 768px) {
        .dashboard-card {
            padding: 25px;
        }

        .dashboard-title {
            font-size: 2rem;
        }

        .dashboard-text {
            font-size: 1rem;
        }
    }
</style>

<div class="container my-5">
    <div class="dashboard-card">
        <h1 class="dashboard-title">Bienvenue sur le tableau de bord de France Academy</h1>
        <p class="dashboard-text">
            Nous sommes ravis de vous accueillir sur la plateforme dédiée à l'accompagnement des lycéens à travers la France et l'Europe.
            Notre mission est de vous fournir les outils et ressources nécessaires pour garantir une éducation de qualité et soutenir le parcours éducatif de chaque étudiant.
        </p>
        <p class="dashboard-text">
            En tant que professeur, vous avez accès à une gamme complète de fonctionnalités pour gérer efficacement les programmes éducatifs,
            suivre les performances des étudiants et gérer correctement les classes ainsi que les examens que vous créez.
        </p>
        <div class="image-container">
            <img src="{{global_asset('dist')}}/images/imagelycee2.jpg" alt="Image du lycée">
        </div>
        <div class="text-center mt-4">
            <button class="btn-primary" data-bs-toggle="modal" data-bs-target="#charteModal">Consulter la charte et les règles</button>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="charteModal" tabindex="-1" aria-labelledby="charteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="charteModalLabel">Charte et Règles de la Plateforme</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul class="nav nav-tabs" id="charteTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="charte-tab" data-bs-toggle="tab" data-bs-target="#charte" type="button" role="tab">Charte</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="regles-tab" data-bs-toggle="tab" data-bs-target="#regles" type="button" role="tab">Règles</button>
                    </li>
                </ul>
                <div class="tab-content mt-3" id="charteTabsContent">
                    <div class="tab-pane fade show active" id="charte" role="tabpanel">
                        <h3>Charte d'utilisation de la plateforme France Academy</h3>
                        <br>
                        <h4>1. Introduction</h4>
                        <p>Bienvenue sur la plateforme <strong>France Academy</strong>, un espace numérique dédié à la gestion et au suivi des activités pédagogiques. Cette charte définit les principes d'utilisation et les règles à respecter pour garantir un environnement respectueux, sécurisé et conforme aux objectifs éducatifs de l'établissement.</p>
                    
                        <h4>2. Objectifs de la plateforme</h4>
                        <ul>
                            <li><strong>Faciliter</strong> la gestion des cours, des classes et des examens.</li>
                            <li><strong>Améliorer</strong> la communication entre professeurs et étudiants.</li>
                            <li><strong>Offrir</strong> un accès centralisé aux ressources pédagogiques.</li>
                            <li><strong>Assurer</strong> le suivi et l’évaluation des étudiants.</li>
                        </ul>
                    
                        <h4>3. Engagements des utilisateurs</h4>
                    
                        <h5>a. Professeurs</h5>
                        <ul>
                            <li>Publier des contenus pédagogiques appropriés et conformes aux programmes scolaires.</li>
                            <li>Respecter la confidentialité des données des étudiants.</li>
                            <li>Utiliser un langage professionnel et respectueux.</li>
                            <li>Assurer une évaluation juste et transparente.</li>
                        </ul>
                    
                        <h5>b. Étudiants</h5>
                        <ul>
                            <li>Respecter les règles de bonne conduite et d’éthique numérique.</li>
                            <li>Consulter régulièrement la plateforme pour suivre leur progression.</li>
                            <li>Ne pas partager leurs identifiants d’accès.</li>
                            <li>Remettre les travaux et examens dans les délais impartis.</li>
                        </ul>
                    
                        <h5>c. Responsabilités générales</h5>
                        <ul>
                            <li>Respecter la charte et les consignes de l’établissement.</li>
                            <li>Ne pas publier de contenu offensant, discriminatoire ou inapproprié.</li>
                            <li>Signaler toute activité suspecte ou tout problème technique aux administrateurs.</li>
                        </ul>
                    </div>
                    
                    <div class="tab-pane fade" id="regles" role="tabpanel">
                        <h3>Règles de conduite sur la plateforme</h3>
                        <br>
                        <h4>1. Respect et courtoisie</h4>
                        <ul>
                            <li>Les propos injurieux, haineux ou discriminatoires sont strictement interdits.</li>
                            <li>Les échanges doivent être bienveillants et constructifs.</li>
                        </ul>
                    
                        <h4>2. Sécurité et confidentialité</h4>
                        <ul>
                            <li>Chaque utilisateur est responsable de la protection de ses identifiants.</li>
                            <li>Il est interdit de partager des informations personnelles sans consentement.</li>
                        </ul>
                    
                        <h4>3. Utilisation des ressources</h4>
                        <ul>
                            <li>Les documents partagés doivent respecter les droits d’auteur.</li>
                            <li>L’usage abusif des ressources numériques est interdit.</li>
                        </ul>
                    
                        <h4>4. Gestion des cours et examens</h4>
                        <ul>
                            <li>Les professeurs sont responsables du contenu et des évaluations.</li>
                            <li>Les étudiants doivent rendre leurs devoirs dans les délais impartis.</li>
                            <li>Toute tentative de fraude ou de triche sera sanctionnée.</li>
                        </ul>
                    
                        <h4>5. Sanctions en cas de non-respect</h4>
                        <p>Selon la gravité des infractions, les sanctions suivantes peuvent être appliquées :</p>
                        <ul>
                            <li><strong>Avertissement écrit</strong> de l’administrateur.</li>
                            <li><strong>Suspension temporaire</strong> ou définitive de l’accès à la plateforme.</li>
                            <li><strong>Signalement à la direction</strong>, pouvant entraîner des sanctions disciplinaires.</li>
                        </ul>
                    </div>                    
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
@endsection
