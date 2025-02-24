@extends('layouts.templateAdmin')

@section('content')
<style>
    .selectionne {
        background-color: #d4edda;
        color: #155724;
    }

    .list-group-item {
        display: flex;
        justify-content: space-between; 
        align-items: center;
        padding: 0.75rem 1.25rem; 
    }

    .list-group-item .form-check-input {
        margin-right: 0.5rem; 
    }

    .list-group-item input[type="number"] {
        width: 60px;
        text-align: center;
        margin-left: 1rem; 
    }

    .modal-content {
        border: none; 
        border-radius: 0.5rem; 
    }

    .modal-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6; 
    }

    .modal-title {
        color: #495057; 
    }

    .btn-primary {
        background-color: #007bff; 
        border-color: #007bff;
    }

    .btn-primary:hover {
        background-color: #0069d9;
        border-color: #0062cc;
    }

    .btn-danger {
        background-color: #dc3545; 
        border-color: #dc3545;
    }

    .btn-danger:hover {
        background-color: #c82333;
        border-color: #bd2130;
    }

    .modal-body ul {
        list-style: none; 
        padding-left: 0; 
    }
    h5 {
        font-size: 1.25rem; 
        color: #3c4141; 
        text-align: center;
        font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
        margin-top: 1rem; 
        margin-bottom: 1rem;
        line-height: 1.5; 
    }

    h1 {
        text-align: center
    }

    .modal-body {
    max-height: 400px; 
    overflow-y: auto; 
    padding-right: 15px; 

    }
.modal-body::-webkit-scrollbar {
    width: 10px;
}

.modal-body::-webkit-scrollbar-track {
    background: #f1f1f1; 
    border-radius: 4px;
}

.modal-body::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 4px;
}

.modal-body::-webkit-scrollbar-thumb:hover {
    background: #555;
}
</style>
<br>
<h1> Filiere {{ $filiere->nom }} - Niveau {{ $filiere->niveau}}</h1>
<h5>{{ $filiere->description }}</h5><BR>

<ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
        <a class="nav-link active" id="matieres-tab" data-bs-toggle="tab" href="#associer-matieres" role="tab"
            aria-controls="associer-matieres" aria-selected="true">Matières</a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link" id="classes-tab" data-bs-toggle="tab" href="#associer-classes" role="tab"
            aria-controls="associer-classes" aria-selected="false">Classes</a>
    </li>
</ul>
<br>

<div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="associer-matieres" role="tabpanel" aria-labelledby="matieres-tab">
        <button type="button" class="btn btn-light border" data-bs-toggle="modal"
            data-bs-target="#associerMatiereModal">
            Ajouter des matières avec les coefficients
        </button>


        <div class="modal fade" id="associerMatiereModal" tabindex="-1" aria-labelledby="associerMatiereModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
              <div class="modal-content">
                <!-- Placez le formulaire pour englober à la fois le body et le footer -->
                <form id="formMatiere" method="POST" action="{{ route('associerMatiere', ['filiere_id' => $filiere->id]) }}">
                  @csrf
                  <div class="modal-header">
                    <h5 class="modal-title" id="associerMatiereModalLabel">Associer des matières à la filière</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <!-- Zone scrollable -->
                  <div class="modal-body">
                    <ul class="list-group">
                      @foreach ($matieresDisponibles as $matiere)
                        @php
                          $coeficient = '';
                          $professeur_id= '';
                          if(count($matiere->filieres)){
                            $coeficient = $matiere->filieres->first()->pivot->coefficient;
                            $professeur_id = $matiere->filieres->first()->pivot->professeur_id;
                          }
                        @endphp
                        <li id="group_{{ $matiere->id }}"
                            class="list-group-item @if(count($matiere->filieres)>0) selectionne @endif">
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox"
                                   name="matieres[{{ $matiere->id }}]" value="{{ $matiere->id }}"
                                   id="matiere_{{ $matiere->id }}" onchange="verifeCheck({{ $matiere->id }})"
                                   @if(count($matiere->filieres)>0) checked @endif>
                            <label class="form-check-label" for="matiere_{{ $matiere->id }}">
                              {{ $matiere->nom }}
                            </label>
                          </div>
                          <input required type="number" id="coefficient_{{ $matiere->id }}"
                                 name="coefficient[{{ $matiere->id }}]" min="1" max="40" step="1"
                                 value="{{ $coeficient }}" @if(count($matiere->filieres) == 0) disabled @endif>
                          <div class="form-check">
                            @php
                              $profMatiereCurrent = $matiereProfs->firstWhere('id', $matiere->id);
                            @endphp
                            <label for="matiere_prof_{{ $matiere->id }}">Responsable de filières</label>
                            <select name="professeur[{{ $matiere->id }}]" id="matiere_prof_{{ $matiere->id }}" @if(count($matiere->filieres) == 0) disabled @endif>
                              <option value="">Aucun responsable</option>
                              @if(is_object($profMatiereCurrent))
                                @foreach($profMatiereCurrent->professeurs as $professeur)
                                  <option @if($professeur->id == $professeur_id) selected @endif value="{{ $professeur->id }}">
                                    {{ $professeur->user->nom }} {{ $professeur->user->prenom }} #{{ $professeur->id }}
                                  </option>
                                @endforeach
                              @endif
                            </select>
                          </div>
                        </li>
                      @endforeach
                    </ul>
                  </div>
                  <div class="modal-footer"> <!-- ici je met les bouton après -->
                    <button type="submit" class="btn btn-primary">Valider</button>
                    <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
          

        <button type="button" class="btn btn-danger" data-bs-toggle="modal"
            data-bs-target="#supprimerMatiereModal">
            Supprimer des matières
        </button>

        <div class="modal fade" id="supprimerMatiereModal" tabindex="-1" aria-labelledby="supprimerMatiereModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="supprimerMatiereModalLabel">Supprimer des matières</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="formSupprimerMatiere" method="POST"
                            action="{{ route('supprimerMatiere', $filiere->id) }}">
                            @csrf
                            <ul class="list-group">
                                @foreach ($filiere->matieres as $matiere)
                                <li class="list-group-item">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                            name="matieres[{{$matiere->id }}]" value="{{ $matiere->id }}"
                                            id="supprimer_{{$matiere->id}}">
                                        <label class="form-check-label" for="supprimer_{{$matiere->id}}">
                                            {{ $matiere->nom }}
                                        </label>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                            <div class="mt-3">
                                <button type="submit" class="btn btn-danger">Supprimer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <table class="table table-striped mt-3">
            <thead>
                <tr>
                    <th scope="col">Nom de la matière</th>
                    <th scope="col">Coefficient</th>
                    <th scope="col">Responsable</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($filiere->matieres as $matiere)
                    @php
                        if($matiere->pivot->professeur_id){
                            $professeur=$matiereProfs->firstWhere('id', $matiere->id)->professeurs->firstWhere('id', $matiere->pivot->professeur_id);
                            $nom_professeur= $professeur->user->nom.' '.$professeur->user->prenom.' #'.$professeur->id;
                        }else  $nom_professeur='N/A';
                    @endphp
                <tr>
                    <td>{{ $matiere->nom }}</td>
                    <td>{{ $matiere->pivot->coefficient }}</td>
                    <td>{{$nom_professeur}} </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="tab-pane fade" id="associer-classes" role="tabpanel" aria-labelledby="classes-tab">
        <table class="table table-striped mt-3">
            <thead>
                <tr>
                    <th scope="col">Id de la classe</th>
                    <th scope="col">Nom de la classe</th>
                    <th scope="col">Nombre d'élèves</th> 
                </tr>
            </thead>
            <tbody>
                @foreach ($filiere->classes as $classe)
                <tr>
                    <td>{{ $classe->id }}</td>
                    <td>{{ $classe->nom }}</td>
                    <td>{{ $classe->eleves->count() }}</td> 
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection   

<script>
function verifeCheck(id) {
    let toto = document.getElementById('matiere_' + id);
    let tata = document.getElementById('coefficient_' + id);
    let groupe = document.getElementById('group_' + id);
    let selectProf = document.getElementById('matiere_prof_' + id);

    if (toto.checked) {
        tata.disabled = false;
        selectProf.disabled = false;
        groupe.classList.add("selectionne");
    } else {
        tata.disabled = true;
        selectProf.disabled = true;
        groupe.classList.remove("selectionne");
    }
}

</script>