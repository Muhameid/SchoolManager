@extends('layouts.templateProfesseur')

@section('content')

@php
$eleves = $examen->eleves;
$filiere = $examen->matiere->filieres->first();
@endphp
<br>

<div class="d-flex justify-content-between align-items-center mb-4">
        
    <h4 class="exam-title">
        <i class="fas fa-file-alt"></i> Note de  l'examen : {{$examen->name}}
    </h4>
    
    <button class="btn btn-secondary btn-sm" onclick="window.history.back()">
        <i class="fas fa-arrow-left"></i> Revenir en arrière
</button>
</div>
<div class="card shadow-sm mt-4">
    <div class="card-body">
        <form id="formMatiere" method="POST" action="{{ route('Note', ['id' => $examen->id]) }}">
            @csrf
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th scope="col" class="text-center">ID</th>
                        <th scope="col" class="text-center">Nom</th>
                        <th scope="col" class="text-center">Prénom</th>
                        <th scope="col" class="text-center">Ine</th>
                        <th scope='col' class="text-center">Filiere</th>
                        <th scope="col" class="text-center">Donner une Note</th>
                        <th scope="col" class="text-center">Observation</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($eleves as $eleve)
                        <tr>
                            <td class="text-center">{{ $eleve->id }}</td>
                            <td class="text-center">{{ $eleve->user->nom }}</td>
                            <td class="text-center">{{ $eleve->user->prenom }}</td>
                            <td class="text-center">{{ $eleve->ine }}</td>
                            <td class="text-center">{{ $eleve->classe->filiere->nom }}</td>
                            <td class="text-center">
                                <input type="number" name="note[{{ $eleve->id }}]" value="{{ $eleve->pivot->note ?? '' }}" class="form-control" min="0" max="20" step="0.01" />
                            </td>
                            <td class="text-center">
                           <textarea name="observation[{{ $eleve->id }}]" value="{{ $eleve->pivot->observation ?? '' }}" placeholder="{{ $eleve->pivot->observation ?? '' }}" class="form-control" >{{ $eleve->pivot->observation ?? '...' }}</textarea>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Bouton de soumission -->
            <div class="form-submit-container d-flex justify-content-end mt-3">
                <button type="submit" class="btn btn-success btn-lg">Valider les notes</button>
            </div>
        </form>
    </div>
</div>
<style>
    .form-submit-container {
        margin-top: 30px;
    }

    .form-submit-container button {
        padding: 10px 30px;
        font-size: 16px;
    }
    
    .table th, .table td {
        text-align: center;
    }

    /* Styles pour les notes */
    .table td input[type="number"] {
        width: 80px;
        margin: 0 auto;
        text-align: center;
    }

    /* Ajout de styles pour améliorer la lisibilité */
    .card-body {
        background-color: #f8f9fa;
    }
</style>

<script>
    // Fonction pour afficher et masquer la désactivation des matières
    function verifeCheck(id) {
        let toto = document.getElementById('matiere_' + id);
        let tata = document.getElementById('coefficient_' + id);
        let groupe = document.getElementById('group_' + id);

        if (toto.checked) {
            tata.disabled = false;
            groupe.classList.add("selectionne");
        } else {
            tata.disabled = true;
            groupe.classList.remove("selectionne");
        }
    }
</script>


@endsection  