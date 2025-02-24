@extends('layouts.templateAdmin')

@section('content')
<div class="container mt-4">

    <div class="d-flex flex-column align-items-center mb-4">
        <h1 class="fw-bold text-center">Gérer vos matières</h2>
        <a class="btn btn-success mt-3" href="/ajoutMatiereAdmin">
            <i class="fas fa-plus-circle"></i> Ajouter une nouvelle matière dans votre établissement
        </a>
    </div>


    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-bordered table-hover" id="matieresTable">
                <thead class="table-dark">
                    <tr>
                        <th scope="col" class="text-center">#</th>
                        <th scope="col" class="text-center">ID</th>
                        <th scope="col" class="text-center">Nom</th>
                        <th scope="col" class="text-center">Description</th>
                        <th scope="col" class="text-center">Fiches</th>
                        <th scope="col" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @php $i = 1; @endphp
                    @foreach ($result as $item)
                        <tr>
                            <th scope="row" class="text-center">{{ $i }}</th>
                            <td class="text-center">{{ $item->id }}</td>
                            <td class="text-center fw-semibold">{{ $item->nom }}</td>
                            <td class="text-center">{{ $item->description }}</td>
                            <td class="text-center">
                                <a class="btn btn-secondary btn-sm" href="{{ route('fiche.matiere', ['matiereId' => $item->id]) }}">
                                    <i class="fas fa-folder-open"></i> Voir
                                </a>
                            </td>
                            <td class="text-center">
                                @if($item->supprimableMatiere())
                                    <a class="btn btn-danger btn-sm" href='/supprimerMatiere/{{$item->id}}' onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette matière ?');">
                                        <i class="fas fa-trash-alt"></i> Supprimer
                                    </a>
                                @else
                                    <span class="badge bg-secondary">Non supprimable</span>
                                @endif
                            </td>
                        </tr>
                        @php $i++; @endphp
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Script DataTables -->
<script>
$(document).ready(function() {
    $('#matieresTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json"
        },
        "pagingType": "full_numbers", // Pagination complète avec boutons numérotés
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