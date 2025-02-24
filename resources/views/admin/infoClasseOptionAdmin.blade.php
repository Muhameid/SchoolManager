@extends('layouts.templateAdmin')

@section('content')
@php

$eleves = $classeoption['eleves'];
$classeoption = $classeoption['classe_option'];
@endphp
<div class="container mt-5">
    <!-- Section Informations de la classe -->
    <div class="row gx-5">
        <div class="col bg-light p-4 rounded-3 shadow-sm border border-3 border-danger">
            <!-- Nom de la classe -->
            <h1 class="text-danger" style="font-family: Arial, sans-serif;">
                Nom de la classe : <span class="text-dark">{{ $classeoption->nom }}</span>
            </h1>

            <!-- Coefficient -->
            <h3 class="text-danger" style="font-family: Arial, sans-serif;">
                Coefficient : <span class="fw-bold text-dark">{{ $classeoption->coefficient ?? 'Non défini' }}</span>
            </h3>

            <!-- Nom de la matière -->
            <h3 class="text-danger" style="font-family: Arial, sans-serif;">
                Nom de la matière : <span class="fw-bold text-dark">{{ $classeoption->matiere->nom ?? 'Non défini' }}</span>
            </h3>

            <!-- Description de la matière -->
            <h3 class="text-danger" style="font-family: Arial, sans-serif;">
                Description de la matière : <span class="fw-bold text-dark">{{ $classeoption->matiere->description ?? 'Aucune description' }}</span>
            </h3>

            <!-- Professeur -->
            <h3 class="text-danger" style="font-family: Arial, sans-serif;">
                Professeur :
                <span class="fw-bold text-dark">
                    @if ($classeoption->professeur)
                        {{ $classeoption->professeur->user->nom }} {{ $classeoption->professeur->user->prenom }}
                        <span class="text-muted">#{{ $classeoption->professeur->id }}</span>
                    @else
                        Aucun professeur assigné
                    @endif
                </span>
            </h3>
        </div>
    </div>

    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#choisirClasseModal-{{ $classeoption->id }}">
        Ajouter un élève
    </button>

    <div class="modal fade" id="choisirClasseModal-{{ $classeoption->id }}" tabindex="-1" aria-labelledby="choisirClasseModalLabel-{{ $classeoption->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="choisirClasseModalLabel-{{ $classeoption->id }}">Attribuer une classe à</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('attribuer_classeOption', ['id' => $classeoption->id]) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="classe_id" class="form-label">Sélectionnez des élèves</label>
                            <select class="form-select" id="classe_id" name="eleve_id" required>
                                @foreach ($eleves as $eleve)
                                <option value="{{ $eleve->id }}">{{ $eleve->user->nom }}#{{ $eleve->ine }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success">Enregistrer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>  
    <!-- Section Liste des élèves -->
    <div class="tab-pane fade show active mt-4" id="associer-matieres" role="tabpanel" aria-labelledby="matieres-tab">
        <div class="table-responsive">
            <table class="table table-hover table-bordered table-striped mt-3">
                <thead class="table-dark">
                    <tr>
                        <th scope="col" class="text-danger">Nom de l'élève</th>
                        <th scope="col" class="text-danger">Prénom de l'élève</th>
                        <th scope="col" class="text-danger">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($classeoption->eleves as $eleve)
                        <tr>
                            <td class="text-danger">{{ $eleve->user->nom }} <span class="text-muted">#{{ $eleve->id }}</span></td>
                            <td class="text-danger">{{ $eleve->user->prenom }}</td>
                            <td>
                                <a type="button" class="btn btn-danger btn-sm" href="{{ route('retirer_classeOption', $eleve->id) }}" onclick="return confirm('Êtes-vous sûr de vouloir retirer cet élève ?');">
                                    Retirer
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-danger">Aucun élève inscrit dans cette classe.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection