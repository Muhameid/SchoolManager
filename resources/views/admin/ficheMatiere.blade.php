@extends('layouts.templateAdmin')

@section('content')
<style>
    body {
        background-color: #f4f4f4;
        font-family: Arial, sans-serif;
    }

    .card.info-matiere {
        background-color: #a91111;
        color: white;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .card-body {
        padding: 2rem;
    }

    .section-title {
    font-size: 1.8rem;
    color: #333;
    text-align: center;
    padding: 10px;
    margin-bottom: 0; 
    font-weight: bold;
    }

    .table.custom-table {
        width: 100%;
        margin-top: 20px; 
        background-color: white;
        border-collapse: collapse;
        border-radius: 5px;
        overflow: hidden;
    }


    .table.custom-table th,
    .table.custom-table td {
        padding: 12px;
        text-align: center;
        font-size: 1rem;
    }

    .table.custom-table th {
        background-color: #333;
        color: white;
        font-weight: bold;
    }

    .table.custom-table tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    .table.custom-table tr:hover {
        background-color: #f1f1f1;
    }

    .table.custom-table td {
        color: #a91111;
    }

    .text-danger {
        color: #a91111;
    }

    h3 {
        font-size: 1.8rem;
        margin-bottom: 20px;
    }

    section {
        border-radius: 8px;
        overflow: hidden;
        background-color: white;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        margin-bottom: 30px;
    }

    .mt-4 {
        margin-top: 40px;
    }

    .mt-5 {
        margin-top: 50px;
    }

    .matiere-id {
        font-weight: bold;
        color: white;
        font-size: 1.4rem; 
    }
</style>

<div class="container mt-5">

    <div class="card info-matiere mb-4">
        <div class="card-body text-center">
            <h1 class="fw-bold">{{ $matiere->nom }}</h1>
            <p class="matiere-id">ID : {{ $matiere->id }}</p>
            <p class="lead">{{ $matiere->description }}</p>
        </div>
    </div>

    <div class="mt-4">
        <div class="section-title">
            <h3>Filières où cette matière est enseignée</h3>
        </div>
        <div class="row justify-content-center">
            @if ($filieres->isEmpty())
                <p class="text-center text-danger">⚠️ Cette matière n'est enseignée dans aucune filière.</p>
            @else
                <div class="col-md-10">
                    <table class="table custom-table">
                        <thead>
                            <tr>
                                <th scope="col">Nom de la Filière</th>
                                <th scope="col">Niveau</th>
                                <th scope="col">Nombre de Classes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($filieres as $filiere)
                                <tr>
                                    <td>{{ $filiere['nom'] }}</td>
                                    <td>{{ $filiere['niveau'] }}</td>
                                    <td>{{ count($filiere['classes'] ?? []) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <div class="mt-5">
        <div class="section-title">
            <h3>Professeurs habilités à enseigner cette matière</h3>
        </div>
        <div class="row justify-content-center">
            @if ($professeurs->isEmpty())
                <p class="text-center text-danger">⚠️ Aucun professeur n'est habilité à enseigner cette matière, RECRUTEZ.</p>
            @else
                <div class="col-md-10">
                    <table class="table custom-table">
                        <thead>
                            <tr>
                                <th scope="col">Identifiant</th>
                                <th scope="col">Nom</th>
                                <th scope="col">Prénom</th>
                                <th scope="col">Email</th>
                                <th scope="col">Téléphone</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($professeurs as $professeur)
                                <tr>
                                    <td>{{ $professeur['identifiant'] }}</td>
                                    <td>{{ $professeur['nom'] }}</td>
                                    <td>{{ $professeur['prenom'] }}</td>
                                    <td>{{ $professeur['email'] }}</td>
                                    <td>{{ $professeur['telephone'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
