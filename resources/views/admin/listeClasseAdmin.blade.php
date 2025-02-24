@extends('layouts.templateAdmin')

@section('content')
<div class="container mt-4">
    <!-- Titre et bouton d'ajout -->
    <div class="d-flex flex-column align-items-center mb-4">
        <h1 class="fw-bold text-center">Gérer vos classes</h1>
        <a class="btn btn-success mt-3" href="/ajoutClasseAdmin">
            <i class="fas fa-plus-circle"></i> Ajouter une nouvelle classe à une filière
        </a>
    </div>

    <!-- Table des classes -->
    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-bordered table-hover" id="tenantTable">
                <thead class="table-dark">
                    <tr>
                        <th scope="col" class="text-center">#</th>
                        <th scope="col" class="text-center">Nom</th>
                        <th scope="col" class="text-center">Filière</th>
                        <th scope="col" class="text-center">Niveau</th>
                        <th scope="col" class="text-center">Élèves</th>
                        <th scope="col" class="text-center">Professeurs et matiere</th>
                        <th scope="col" class="text-center">Matiere attribuées</th>
                        <th scope="col" class="text-center">Fiches</th>
                        <th scope="col" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($result as $item)

                        <tr>
                            <th scope="row" class="text-center">{{ $item->id }}</th>
                            <td class="text-center fw-semibold">{{ $item->nom }}</td>
                            <td class="text-center">{{ $item->filiere->nom ?? 'Aucune filière' }}</td>
                            <td class="text-center">{{ $item->filiere->niveau ?? 'Aucune filière' }}</td>
                            <td class="text-center">{{ $item->eleves->count() }}</td>
                            <td class="text-center">{{ $item->professeurs->count() ?? '0' }}</td>
                            <td class="text-center">{{ $item->matieres->count() ?? '0' }}/{{ $item->filiere->matieres->count() ?? '0' }}</td>
                            <td class="text-center">
                                <a class="btn btn-primary btn-sm" href='/info_classe/{{$item->id}}'>
                                    <i class="fas fa-eye"></i> Voir
                                </a>
                            </td>
                            <td class="text-center">
                                @if($item->supprimable())
                                    <a class="btn btn-danger btn-sm" href='/supprimer_classe/{{$item->id}}' onclick="return confirm('Voulez-vous vraiment supprimer cette classe ?');">
                                        <i class="fas fa-trash-alt"></i> Supprimer
                                    </a>
                                @else
                                    <span class="badge bg-secondary">Non supprimable</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Messages de succès / erreur -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!-- Script DataTables -->
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
