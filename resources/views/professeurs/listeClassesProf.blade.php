@extends('layouts.templateProfesseur')

@section('content')

<style>
  h2 {
    font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
  }
</style>

<div class="container mt-4">
    <!-- Header Section with Title -->
    <div class="d-flex flex-column align-items-center mb-4">
        <h2 class="fw-bold text-center">GESTION DES CLASSES</h2>
        <p class="text-muted text-center">Visualisez et gérez vos classes, consultez les élèves et les matières associées.</p>
    </div>

    <!-- Tabs for Class Options and Responsibilities -->
    <ul class="nav nav-tabs mb-4" id="classeTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="classe-tab" data-bs-toggle="tab" href="#classe" role="tab" aria-controls="classe" aria-selected="true">Classes</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="classe-options-tab" data-bs-toggle="tab" href="#classe-options" role="tab" aria-controls="classe-options" aria-selected="false">Classe Options</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="responsabilites-tab" data-bs-toggle="tab" href="#responsabilites" role="tab" aria-controls="responsabilites" aria-selected="false">Responsabilités</a>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="classeTabsContent">
        <!-- Classes Tab -->
        <div class="tab-pane fade show active" id="classe" role="tabpanel" aria-labelledby="classe-tab">
            <!-- Your Classes Content Here -->
            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="text-center mb-3">Liste de mes classes</h3>
                    <table class="table table-bordered table-hover dataTable">
                        <thead class="table-dark">
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">Nom de la Classe</th>
                                <th class="text-center">Nom de la Matière</th>
                                <th class="text-center">Description</th>
                                <th class="text-center">Nombre d'élèves</th>
                                <th class="text-center">Fiches</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($result['listeClassesProf'] as $item)
                                @foreach ($item->classes as $classe)
                                    @foreach ($classe->matieres as $matiere)
                                        <tr>
                                            <td class="text-center fw-semibold">{{ $classe->id }}</td>
                                            <td class="text-center">{{ $classe->nom ?? 'Aucune classe' }}</td>
                                            <td class="text-center">{{ $matiere->nom ?? 'Aucune matière' }}</td>
                                            <td class="text-center">{{ $matiere->description ?? 'Aucune Description' }}</td>
                                            <td class="text-center">{{ $classe->eleves->count() ?? 0 }}</td>
                                            <td class="text-center">
                                                <a class="btn btn-primary btn-sm" href="#" data-bs-toggle="modal" data-bs-target="#voirModal{{$classe->id}}">
                                                    <i class="fas fa-users"></i> Voir
                                                </a>
                                            </td>
                                         
                                        </tr>
                                    @endforeach
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Classe Options Tab (Copied from Classes Tab) -->
        <div class="tab-pane fade" id="classe-options" role="tabpanel" aria-labelledby="classe-options-tab">
            <!-- Your Classe Options Content Here -->
            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="text-center mb-3">Liste de mes classes (Options)</h3>
                    <table class="table table-bordered table-hover dataTable">
                        <thead class="table-dark">
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">Nom de la Classe</th>
                                <th class="text-center">Nom de la Matière</th>
                                <th class="text-center">Description</th>
                                <th class="text-center">Nombre d'élèves</th>
                               
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($result['listeOptionsProf'] as $item)
                                @foreach ($item->classeOptions as $classeOptions)
                                    <tr>
                                        <td class="text-center fw-semibold">{{ $classeOptions->id ?? 'Aucune classe' }}</td>
                                        <td class="text-center">{{ $classeOptions->nom ?? 'Aucune classe' }}</td>
                                        <td class="text-center">{{ $classeOptions->matiere->nom ?? 'Aucune matière' }}</td>
                                        <td class="text-center">{{ $classeOptions->eleves->count() ?? 0 }}</td>
                                        <td class="text-center">
                                            <a class="btn btn-primary btn-sm" href='/listeElevesProf/{{$item->id}}' aria-label="Voir les élèves">
                                                <i class="fas fa-users"></i> Voir
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Responsabilités Tab -->
        <div class="tab-pane fade" id="responsabilites" role="tabpanel" aria-labelledby="responsabilites-tab">
            <!-- Your Responsabilités Content Here -->
            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="text-center mb-3">Responsabilités du Professeur</h3>
                    <!-- Content for responsibilities -->
                    <ul>
                        <li>Gestion des examens</li>
                        <li>Supervision des élèves</li>
                        <li>Gestion des classes et matières</li>
                        <li>Participation aux réunions pédagogiques</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Modal Voir les élèves -->
@foreach ($result['listeClassesProf'] as $item)
    @foreach ($item->classes as $classe)
        <div class="modal fade" id="voirModal{{$classe->id}}" tabindex="-1" aria-labelledby="voirModalLabel{{$classe->id}}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="voirModalLabel{{$classe->id}}">Liste des élèves de la classe : {{ $classe->nom }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <ul>
                            @foreach ($classe->eleves as $eleve)
                                <li>#{{ $eleve->user->id }} {{ $eleve->user->nom }} {{ $eleve->user->prenom }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    </div>
                </div>
            </div>
        </div>

      
    @endforeach
@endforeach

@if(session('success'))
    <div class="alert alert-success mt-3">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger mt-3">
        {{ session('error') }}
    </div>
@endif

<!-- Ajout de DataTables pour améliorer l'affichage du tableau -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('.dataTable').DataTable({
            "paging": true, 
            "searching": true, 
            "ordering": true, 
            "lengthChange": false, 
            "pageLength": 5,
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.21/i18n/French.json" 
            }
        });
    });
</script>

@endsection
