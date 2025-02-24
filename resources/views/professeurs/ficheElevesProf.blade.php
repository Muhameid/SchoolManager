@extends('layouts.templateProfesseur')
@section('content')
<div class="container-center " style="margin-top:50px">
    <div class="row gx-5">
      <div class="col bg-danger bg-opacity-50 border border-danger border-3 rounded-en">
          <h4 style="font-family: Arial">Identifiant: <span class="value">{{$professeurs->identifiant}}</span></h4>
          <h4 style="font-family: Arial">Login: <span class="value">{{$professeurs->user->login}}</span></h4>
          <h4 style="font-family: Arial">Nom: <span class="value">{{$professeurs->user->nom}}</span></h5>
          <h4 style="font-family: Arial">Prénom: <span class="value">{{$professeurs->user->prenom}}</span></h5>
        </div>

        <div class="col bg-black bg-opacity-25 border border-black border-3 rounded-en">
          <h5 style="font-family: Arial">Adresse: <span class="value">{{$professeurs->user->adresse}}</span></h5>
          <h5 style="font-family: Arial">Ville: <span class="value">{{$villes->nom}}</span></h5>
          <h5 style="font-family: Arial">Pays: <span class="value">{{$pays->nom}}</span></h5>
          <h5 style="font-family: Arial">Date de naissance: <span class="value">{{date('d-m-Y', strtotime($professeurs->user->date_naissance))}}</span></h5>
          <h5 style="font-family: Arial">Numéro de telephone: <span class="value">{{$professeurs->user->telephone_1}}</span></h5>
        </div>
    </div>
</div>




    <!-- Onglets -->
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

    <!-- Contenu des onglets -->
    <div class="tab-content" id="myTabContent">

        <!-- Onglet Matières -->
        <div class="tab-pane fade show active" id="associer-matieres" role="tabpanel" aria-labelledby="matieres-tab">
            <!-- Bouton pour ajouter/associer une matière -->
            <button type="button" class="btn btn-light border" data-bs-toggle="modal"
                data-bs-target="#associerMatiereModal">
                Associer une matière
            </button>

            <!-- Modal pour associer une matière -->
            <div class="modal fade" id="associerMatiereModal" tabindex="-1" aria-labelledby="associerMatiereModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="associerMatiereModalLabel">Associer des matières au professeur</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                        <form method="POST" action="{{ route('associationProfesseurMatiere')}}">
                                @csrf
                                <ul class="list-group">
                                    <input type='hidden' name='professeur_id' value='{{$professeurs->id}}'>
                                    @foreach ($matieres as $matiere)
                                        <li class="list-group-item">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="matieres[{{ $matiere->id }}]"
                                                    value="{{ $matiere->id }}" id="matiere_{{ $matiere->id }}"
                                                    @if(is_object($matiere->professeurs) && $matiere->professeurs->count()==1 )checked @endif    
                                                >
                                                <label class="form-check-label" for="matiere_{{ $matiere->id }}">
                                                    {{ $matiere->nom }}
                                                </label>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                                <div class="mt-3">
                                    <button type="submit" class="btn btn-primary">Associer</button>
                                    <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal pour désassocier une matière -->
            <div class="modal fade" id="desassocierMatiereModal" tabindex="-1" aria-labelledby="desassocierMatiereModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="desassocierMatiereModalLabel">Désassocier des matières du professeur</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="{{ route('desassociationProfesseurMatiere', ['professeur_id' => $professeurs->id]) }}">
                                @csrf
                               
                                @if( $professeurs->matieres->count() > 0)
                                @foreach ($professeurs->matieres as $matiere)
                                    
                                    <li class="list-group-item">
    
                                        <div class="form-check">
    
                                            <input class="form-check-input" type="checkbox" name="matieres[{{ $matiere->id }}]"
                                                value="{{ $matiere->id }}" id="matiere_{{ $matiere->id }}">
    
                                            <label class="form-check-label" for="matiere_{{ $matiere->id }}">
                                                {{ $matiere->nom }}
                                            </label>
    
                                        </div>
    
                                    </li>
                                @endforeach
                            @else
                                <li class="list-group-item">Aucune matière associée</li>
                            @endif
                                </ul>
                                <div class="mt-3">
                                    <button type="submit" class="btn btn-danger">Désassocier</button>
                                    <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table pour afficher les matières associées -->
            <table class="table table-striped mt-3">
                <thead>
                    <tr>
                        <th scope="col">Nom de la matière</th>
                        <th scope="col">Description de la matière</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($professeurs->matieres as $matiere)
                        <tr>
                            <td>{{ $matiere->nom }} #{{$matiere->id }}</td>
                            <td>{{ $matiere->description }}</td>                          
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Onglet Classes -->
        <div class="tab-pane fade" id="associer-classes" role="tabpanel" aria-labelledby="classes-tab">
            <table class="table table-striped mt-3">
                <thead>
                    <tr>
                        <th scope="col">Matiere enseigné</th>
                        <th scope="col">Nom de la classe</th>
                        <th scope="col">Nnom de la filière</th>
                        <th scope="col">Niveau de la filiere</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($professeurs->classes as $classe)
                        @php
                            $matiere_id=$classe->pivot->matiere_id;
                            $matiere=$professeurs->matieres->firstWhere('id', $matiere_id); 
                            if(!is_object($matiere))dd('probleme');

                        @endphp 
                        <tr>
                            <th>{{ $matiere->nom}} #{{$matiere_id }} </th>
                            <td>{{ $classe->nom }} #{{ $classe->id}} </td>
                            <td>{{ $classe->filiere->nom }}  #{{ $classe->filiere->id}}</td>
                            <td>{{ $classe->filiere->niveau }} </td>
                            
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>

@endsection

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