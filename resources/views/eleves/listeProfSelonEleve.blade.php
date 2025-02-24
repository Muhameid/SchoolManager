@extends('layouts.templateEleve')

@section('content')
<style>

    :root {
        --primary-color:#333333; 
        --secondary-color: #333333; 
        --light-color: #f8f9fa; 
        --text-color: #495057; 
    }

    body {
        background-color: var(--light-color);
        color: var(--text-color);
    }

    .container {
        max-width: 900px; 
    }

    /* Titres */
    h2 {
        font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
        font-size: 2.2rem; 
        color: var(--primary-color);
        text-transform: uppercase;
        letter-spacing: 2px; 
        font-weight: bold; 
    }

    h4 {
        font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
        font-size: 1.6rem;
        color: var(--secondary-color); 
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: bold;
        margin-bottom: 1rem;
    }


    .nav-tabs .nav-link {
        background-color: white;
        border: 1px solid #dee2e6;
        color: var(--text-color);
        margin-bottom: -1px;
        padding: 0.75rem 1.5rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .nav-tabs .nav-link.active,
    .nav-tabs .nav-item.show .nav-link {
        background-color: var(--primary-color);
        color: white;
        border-color: var(--primary-color) var(--primary-color) #fff;
    }

    .nav-tabs .nav-link:hover {
        background-color: #e9ecef;
    }

    /* Tableaux */
    .table {
        background-color: white;
        border-radius: 0.5rem;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
    }

    .table thead th {
        background-color: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
        font-weight: bold;
        padding: 1rem;
    }

    .table tbody td {
        padding: 1rem;
        border-color: #dee2e6;
    }

    .table-bordered {
        border-width: 2px;
        border-color: #dee2e6;
    }

    .table-hover tbody tr:hover {
        background-color: #f5f5f5;
    }

    .table-responsive {
    overflow-x: auto; 
    }

</style>

<div class="container mt-4">
    <h2 class="text-center my-4">Mes Cours Suivis</h2>

    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="ma-classe-tab" data-bs-toggle="tab" href="#ma-classe" role="tab" aria-controls="ma-classe" aria-selected="true">Ma Classe</a> 
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="option-tab" data-bs-toggle="tab" href="#option" role="tab" aria-controls="option" aria-selected="false">Option</a>
        </li>
    </ul>

    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="ma-classe" role="tabpanel" aria-labelledby="ma-classe-tab">
            <h4 class="mt-3">Liste des Professeurs et leurs Matières</h4>
            <div class="table-responsive">
               <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                    <tr>
                        <th>Nom du Professeur</th>
                        <th>Matières enseignées</th>
                        <th>Coefficient</th>
                    </tr>
                    </thead>
                   <tbody>
                    @foreach($result as $professeur)
                     <tr>
                            <td>{{ $professeur['professeur'] }}</td>
                           <td>{{ $professeur['matiere'] }}</td>
                            <td>{{ $professeur['coefficient'] }}</td>
                      </tr>
                   @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="tab-pane fade" id="option" role="tabpanel" aria-labelledby="option-tab">
            <h4 class="mt-3">Liste des Professeurs et leurs Matières pour l'Option</h4>
            @if(count($resultOption) > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Nom du Professeur</th>
                                <th>Nom de la classe option</th>
                                <th>Matières enseignées</th>
                                <th>Coefficient</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($resultOption as $professeur)
                            
                                <tr>
                                    <td>{{ $professeur['professeurOption'] }}</td>
                                    <td>{{ $professeur['nomOption'] }}</td>
                                    <td>{{ $professeur['matiereOption'] }}</td>
                                    <td>{{ $professeur['coefficientOption'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p>Aucune option disponible.</p>
            @endif
        </div>
</div>
@endsection