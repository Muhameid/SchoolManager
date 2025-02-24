@extends('layouts.templateAdmin')

@section('content')

<style>
    .fw-bold h1{
        font-family: 'Exo 2', serif;
    }
</style>

<div class="container mt-4">

    <div class="d-flex flex-column align-items-center mb-4">
        <h1 class="fw-bold text-center">Gérer vos filières</h2>
        <a class="btn btn-success mt-3" href="/AjoutFiliereAdmin">
            <i class="fas fa-plus-circle"></i> Ajouter une filière
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-bordered table-hover" id="filieresTable">
                <thead class="table-dark">
                    <tr>
                        <th scope="col" class="text-center">#</th>
                        <th scope="col" class="text-center">ID</th>
                        <th scope="col" class="text-center">Nom</th>
                        <th scope="col" class="text-center">Niveau</th>
                        <th scope="col" class="text-center">Nombre d'élèves</th>
                        <th scope="col" class="text-center">Fiches</th>
                        <th scope="col" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @php $i = 1; @endphp
                    @foreach ($result as $filiere)
                        <tr>
                            <th scope="row" class="text-center">{{ $i }}</th>
                            <td class="text-center">{{ $filiere->id }}</td>
                            <td class="text-center fw-semibold">{{ $filiere->nom }}</td>
                            <td class="text-center">{{ $filiere->niveau }}</td>
                            <td class="text-center">{{ $filiere->classes->sum(fn($classe) => $classe->eleves()->count()) }}</td>
                            <td class="text-center">
                                <a class="btn btn-secondary btn-sm" href="{{ route('filiere.associer', ['filiere_id' => $filiere->id]) }}">
                                    <i class="fas fa-folder-open"></i> Voir
                                </a>
                            </td>
                            <td class="text-center">
                                <a class="btn btn-danger btn-sm" href="/supprimmer_filiere/{{$filiere->id}}" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette filière ?');">
                                    <i class="fas fa-trash-alt"></i> Supprimer
                                </a>
                            </td>
                        </tr>
                        @php $i++; @endphp
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>


<script>
$(document).ready(function() {
    $('#filieresTable').DataTable({
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