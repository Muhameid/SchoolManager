@extends('layouts.templateAdmin')

@section('content')
<div class="container mt-4">
    <div class="d-flex flex-column align-items-center mb-4">
        <h1 class="fw-bold text-center">Gérer vos élèves</h1>
        <a class="btn btn-success mt-3" href="/ajoutEleveAdmin">
            <i class="fas fa-plus-circle"></i> Ajouter un nouvel élève à votre établissement
        </a>
    </div>

    <!-- Table des élèves -->
    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-bordered table-hover" id="tenantTable">
                <thead class="table-dark">
                    <tr>
                        <th scope="col" class="text-center">#</th>
                        <th scope="col" class="text-center">Nom de l'élève</th>
                        <th scope="col" class="text-center">Prénom de l'élève</th>
                        <th scope="col" class="text-center">INE</th>
                        <th scope="col" class="text-center">Nom de la classe</th>
                        <th scope="col" class="text-center">Filiere</th>
                        <th scope="col" class="text-center">Classe Options</th>
                        <th scope="col" class="text-center">Fiche</th>
                        <th scope="col" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($result as $item)
                        <tr>
                            <th scope="row" class="text-center">{{ $item->id }}</th>
                            <td class="text-center fw-semibold">{{ $item->user->nom }}</td>
                            <td class="text-center">{{ $item->user->prenom }}</td>
                            <td class="text-center">{{ $item->ine }}</td>
                            <td class="text-center">
                                @if ($item->classe)
                                    {{ $item->classe->nom }}
                                @else
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#choisirClasseModal-{{ $item->id }}">
                                        Choisir une classe
                                    </button>

                                    <!-- Modal -->
                                    <div class="modal fade" id="choisirClasseModal-{{ $item->id }}" tabindex="-1" aria-labelledby="choisirClasseModalLabel-{{ $item->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="choisirClasseModalLabel-{{ $item->id }}">Attribuer une classe à {{ $item->user->nom }} {{ $item->user->prenom }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('attribuer_classe', ['id' => $item->id]) }}" method="POST">
                                                        @csrf
                                                        <div class="mb-3">
                                                            <label for="classe_id" class="form-label">Sélectionnez une classe</label>
                                                            <select class="form-select" id="classe_id" name="classe_id" required>
                                                                @foreach ($classes as $classe)
                                                                    <option value="{{ $classe->id }}">{{ $classe->nom }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <button type="submit" class="btn btn-success">Enregistrer</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </td>
                            <td class="text-center">{{ $item->classe->filiere->nom ?? 'aucune filiere'}}</td>

                            <td class="text-center">{{ $item->classe_options->count() }}</td>
                            <td class="text-center">                                
                                <form action="{{ route('fiche_eleve', ['id' => $item->id]) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary" >
                                    <i class="fas fa-trash-alt"></i> Voir
                                </button>
                            </form>
                        </td>
                            <td class="text-center">
                                <form action="{{ route('supprimer_eleve', ['id' => $item->id]) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Voulez-vous vraiment supprimer cet élève ?');">
                                        <i class="fas fa-trash-alt"></i> Supprimer
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#tenantTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json"
            },
            "pagingType": "full_numbers",
            "scrollY": "500px",
            "scrollCollapse": true,
            "paging": true,
            "dom": "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" + 
                   "<'row'<'col-sm-12'tr>>" + 
                   "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            "drawCallback": function() {
                $(".pagination").addClass("pagination-sm justify-content-center");
            }
        });
    });


</script>

@endsection
