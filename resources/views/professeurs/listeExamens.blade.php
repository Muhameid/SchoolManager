@extends('layouts.templateProfesseur')

@section('content')

<div class="container mt-4">
    <!-- Section Titre et Bouton -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-primary">📌 Gestion des examens</h2>
            <p class="text-muted">Ajoutez, visualisez et gérez vos examens.</p>
        </div>
        <a href="/creation_examen" class="btn btn-success">
            <i class="fas fa-plus"></i> Ajouter un examen
        </a>
    </div>

    <!-- Tableau Stylisé -->
    <div class="card shadow-lg rounded-3">
        <div class="card-body">
            <table class="table table-hover align-middle dataTable" id="tenantTable">
                <thead class="table-dark">
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">Nom</th>
                        <th class="text-center">Matière</th>
                        <th class="text-center">Coefficient</th>
                        <th class="text-center">Nombre d'élèves</th>
                        <th class="text-center">Fiche</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($result as $item)
                        <tr>
                            <td class="text-center fw-semibold">{{$item->id}}</td>
                            <td class="text-center">{{$item->name}}</td>
                            <td class="text-center">{{$item->matiere->nom}}</td>
                            <td class="text-center">{{$item->coefficient}}</td>
                            <td class="text-center">{{$item->eleves->count()}}</td>
                            <td class="text-center">
                                <a class="btn btn-outline-primary btn-sm" href='/FicheExamen/{{$item->id}}'>
                                    <i class="fas fa-eye"></i> Voir
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Ajout de DataTables pour améliorer le tableau -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#tenantTable').DataTable({
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
