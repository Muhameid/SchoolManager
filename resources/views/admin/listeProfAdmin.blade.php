@extends('layouts.templateAdmin')

@section('content')
<div class="container mt-4">
 
    <div class="d-flex flex-column align-items-center mb-4">
        <h1 class="fw-bold text-center">Gérer vos professeurs</h1>
        <a class="btn btn-success mt-3" href="/ajoutProfAdmin">
            <i class="fas fa-plus-circle"></i> Ajouter un nouveau professeur à votre établissement
        </a>
    </div>

    <!-- Table des professeurs -->
    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-bordered table-hover" id="professeursTable">
                <thead class="table-dark">
                    <tr>
                        <th scope="col" class="text-center">#</th>
                        <th scope="col" class="text-center">Identifiant</th>
                        <th scope="col" class="text-center">Nom</th>
                        <th scope="col" class="text-center">Fiche</th>
                        <th scope="col" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($result as $index => $item)
                        <tr>
                            <th scope="row" class="text-center">{{ $index + 1 }}</th>
                            <td class="text-center">{{ $item->identifiant }}</td>
                            <td class="text-center fw-semibold">{{ $item->user->nom }} {{ $item->user->prenom }}</td>
                            <td class="text-center">
                                <a class="btn btn-primary btn-sm" href='/ficheProfesseur/{{$item->id}}'>
                                    <i class="fas fa-eye"></i> Voir
                                </a>
                            </td>
                            <td class="text-center">
                                <a class="btn btn-danger btn-sm" href='/supprimerProfesseur/{{$item->id}}' onclick="return confirm('Voulez-vous vraiment supprimer ce professeur ?');">
                                    <i class="fas fa-trash-alt"></i> Supprimer
                                </a>
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
    $('#professeursTable').DataTable({
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
