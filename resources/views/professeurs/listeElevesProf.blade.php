@extends('layouts.templateProfesseur')

@section('content')

<style>
  h2 {
    font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
  }
</style>

<div class="container mt-4">
    <div class="d-flex flex-column align-items-center mb-4">
        <h2 class="fw-bold text-center">GESTION DES CLASSES</h2>
        <p class="text-muted text-center">Visualisez et gérez vos classes, consultez les élèves et les matières associées.</p>
    </div>

    <!-- Navigation des onglets -->
    <ul class="nav nav-tabs" id="examTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="classes-tab" data-bs-toggle="tab" data-bs-target="#classes" type="button" role="tab" aria-controls="classes" aria-selected="true">Classes</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="classes-options-tab" data-bs-toggle="tab" data-bs-target="#classes-options" type="button" role="tab" aria-controls="classes-options" aria-selected="false">Options</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="responsabilite-tab" data-bs-toggle="tab" data-bs-target="#responsabilite" type="button" role="tab" aria-controls="responsabilite" aria-selected="false">Responsabilité</button>
        </li>
    </ul>

    <!-- Contenu des onglets -->
    <div class="tab-content mt-3" id="examTabsContent">

        <!-- Onglet "Classes" -->
        <div class="tab-pane fade show active" id="classes" role="tabpanel" aria-labelledby="classes-tab">
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
                                <th class="text-center">Nom de la filiére</th>
                                <th class="text-center">Nombre d'élèves</th>
                                <th class="text-center">Fiches</th>
                                <th class="text-center">Actions</th>
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
                                            <td class="text-center">{{ $classe->filiere->nom ?? 'Aucune Filiére' }}</td>
                                            <td class="text-center">{{ $classe->eleves->count() ?? 0 }}</td>
                                            <td class="text-center">
                                                <a class="btn btn-primary btn-sm" href="#" data-bs-toggle="modal" data-bs-target="#voirModal{{$classe->id}}">
                                                    <i class="fas fa-users"></i> Voir
                                                </a>
                                            </td>
                                            <td>
                                                <a class="btn btn-success btn-sm" href='/listeElevesProf/{{$item->id}}' aria-label="Associer à un examen">
                                                    <i class="fas fa-users"></i> Associer à un examen
                                                </a>
                                            </td>
                                        </tr>

                                        <!-- Modal Voir les élèves -->
                                        <div class="modal fade" id="voirModal{{$classe->id}}" tabindex="-1" aria-labelledby="voirModalLabel{{$classe->id}}" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="voirModalLabel{{$classe->id}}">Liste des élèves de la classe : {{ $classe->nom }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <h5>Matière : {{ $matiere->nom ?? 'Non définie' }}</h5>
                                                        <p>{{ $matiere->description ?? 'Pas de description pour cette matière.' }}</p>
                                                        
                                                        <!-- Liste des élèves -->
                                                        <div class="container">
                                                            <table class="table table-bordered table-hover">
                                                                <thead class="table-dark">
                                                                    <tr>
                                                                        <th scope="col" class="text-center">ID</th>
                                                                        <th scope="col" class="text-center">Nom</th>
                                                                        <th scope="col" class="text-center">Prénom</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                   
                                                                    @foreach ($classe->eleves as $eleve)
                                                                        <tr>
                                                                            <td class="text-center">{{ $eleve->user->id }}</td>
                                                                            <td class="text-center">{{ $eleve->user->nom }}</td>
                                                                            <td class="text-center">{{ $eleve->user->prenom }}</td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Onglet "Classes Options" -->
        <div class="tab-pane fade" id="classes-options" role="tabpanel" aria-labelledby="classes-options-tab">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="text-center mb-3">Liste des Classes avec Options</h3>
                    <table class="table table-bordered table-hover dataTable">
                        <thead class="table-dark">
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">Nom de la Classe</th>
                                <th class="text-center">Nom de la Matière</th>
                                <th class="text-center">Nombre d'élèves</th>
                                <th class="text-center">Actions</th>
                                <th class="text-center">Fiches</th>
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
                                        <td>
                                            <a class="btn btn-success btn-sm" href='/listeElevesProf/{{$item->id}}' aria-label="Associer à un examen">
                                                <i class="fas fa-users"></i> Associer à un examen
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

        <!-- Onglet "Responsabilité" -->
        <div class="tab-pane fade" id="responsabilite" role="tabpanel" aria-labelledby="responsabilite-tab">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="text-center mb-3">Liste des Responsabilités</h3>
                    <table class="table table-bordered dataTable">
                        <thead class="table-dark">
                            <tr>
                                <th class="text-center">Nom de la classe</th>
                                <th class="text-center">Rôle</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center">
                                    <button class="btn btn-info btn-sm">Voir Détails</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

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
</div>

<!-- Ajout de DataTables pour les tableaux -->
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
