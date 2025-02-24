@extends('layouts.templateProfesseur')

@section('content')

<div class="container mt-5">
    <!-- Titre Stylisé -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        
        <h4 class="exam-title">
            <i class="fas fa-file-alt"></i> Détails de l'examen : {{$examen->name}}
        </h4>
        
        <button class="btn btn-secondary btn-sm" onclick="window.history.back()">
            <i class="fas fa-arrow-left"></i> Revenir en arrière
</button>
    </div>

   <!-- Tableau d'informations -->
<div class="card shadow-lg rounded-3 mb-4">
    <div class="card-body">
        <h5 class="text-dark fw-bold">Informations générales</h5>
        <div class="row">
            <!-- Première colonne -->
            <div class="col-md-6">
                <table class="table table-bordered table-striped">
                    <tbody>
                        <tr>
                            <th>Id</th>
                            <td>#{{$examen->id}}</td>
                        </tr>
                        <tr>
                            <th>Nom</th>
                            <td>{{$examen->name}}</td>
                        </tr>
                        <tr>
                            <th>Matiere</th>
                            <td>{{$examen->matiere->nom}}</td>
                        </tr>
                        <tr>
                            <th>Date</th>
                            <td>{{ \Carbon\Carbon::parse($examen->date_examen)->format('d-m-Y à H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Sujet</th>
                            <td>{{$examen->sujet}}</td>
                        </tr>
                        <tr>
                            <th>Observation</th>
                            <td>
                                @if($examen->observation)
                                    {{$examen->observation}}
                                @else
                                    <span class="text-muted">Pas d'observation</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Lien</th>
                            <td>
                                @if($examen->lien)
                                    <a href="{{ route('download.examen', ['filename' => $examen->lien]) }}" target="_blank" class="btn btn-outline-primary btn-sm">Accéder</a>
                                @else
                                    <span class="text-muted">Pas de lien</span>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Deuxième colonne -->
            <div class="col-md-6">
                <table class="table table-bordered table-striped">
                    <tbody>
                        <tr>
                            <th>Coefficient</th>
                            <td>{{$examen->coefficient}}</td>
                        </tr>
                        <tr>
                            <th>Note Maximale</th>
                            <td>{{$examen->note_maximale ?? 'Non disponible'}}</td>
                        </tr>
                        <tr>
                            <th>Note Minimale</th>
                            <td>{{$examen->note_minimale ?? 'Non disponible'}}</td>
                        </tr>
                        <tr>
                            <th>Moyenne générale</th>
                            <td>{{$examen->moyenne_generale ?? 'Non disponible'}}</td>
                        </tr>
                        <tr>
                            <th>Nombre d'éléves participantes</th>
                            <td>{{$examen->eleves->count() ?? 'Non disponible'}}</td>
                        </tr>
                        <tr>
                            <th>Actions</th>
                            <td>
                                <a href="/PageNote/{{$examen->id}}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus"></i> Ajouter des notes
                                </a>
                                <a href='/supprimerExamen/{{$examen->id}}' 
                                    class="btn btn-danger btn-sm" 
                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet examen ?');">
                                     <i class="fas fa-trash-alt"></i> Supprimer
                                 </a>
                                 
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

    <!-- Onglets -->
    <ul class="nav nav-tabs" id="examTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="students-associated-tab" data-bs-toggle="tab" href="#students-associated" role="tab" aria-controls="students-associated" aria-selected="true">Voir les élèves inscrits</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="add-students-tab" data-bs-toggle="tab" href="#add-students" role="tab" aria-controls="add-students" aria-selected="false">Associer des élèves</a>
        </li>
    </ul>
    <div class="tab-content mt-4" id="examTabsContent">
        <!-- Voir les élèves inscrits -->
        <div class="tab-pane fade show active" id="students-associated" role="tabpanel" aria-labelledby="students-associated-tab">
            <h4 class="text-center text-dark mb-4">Élèves inscrits à cet examen</h4>
    
            <form method="POST" action="{{ route('retirer_examen_eleve') }}">             
                   @csrf
                <input type="hidden" name="examen_id" value="{{ $examen->id }}">
    
                @foreach($professeur->classes as $classe)
                    @php
                        // Filtrer les élèves de cette classe qui sont inscrits à l'examen
                        $eleves_inscrits = $examen->eleves->where('classe_id', $classe->id);
                    @endphp
    
                    @if($eleves_inscrits->count() > 0)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="classe_{{$classe->id}}" name="classes[]" value="{{$classe->id}}" onchange="toggleEleves('classe_{{$classe->id}}', 'eleves_classe_{{$classe->id}}')">
                            <label class="form-check-label fw-bold" for="classe_{{$classe->id}}">
                                #{{$classe->id}} {{$classe->nom}} - Filière : {{$classe->filiere->nom}}
                            </label>
                        </div>
    
                        <div class="eleves-list ms-3" id="eleves_classe_{{$classe->id}}">
                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th>Sélectionner</th>
                                       
                                        <th>INE</th>
                                        <th>Prénom</th>
                                        <th>Nom</th>
                                        
                                       
                                     
                                        <th>Note</th>
                                        <th>Fiche</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($eleves_inscrits as $e)
                                        <tr>
                                            <td>
                                                <input class="form-check-input" type="checkbox" name="eleves[]" value="{{$e->id}}" 
                                                    @if($examen->eleves->contains($e->id)) unchecked @endif
                                                    onchange="checkClasse('classe_{{$classe->id}}', 'eleves_classe_{{$classe->id}}')" 
                                                    @if($examen->eleves->contains($e->id))  @endif>
                                            </td>
                                           
                                            <td>{{$e->ine}}</td>
                                            <td>{{$e->user->prenom}}</td>
                                            <td>{{$e->user->nom}}</td>
                                           
                                           
                                           
                                            <td>{{$e->pivot->note ?? 'Pas de note'}}</td>
                                            <td>
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ficheEleveModal{{$e->id}}">
                                                    Voir
                                                </button>
                                            </td>
                                        </tr>
    
                                        <!-- Modal (exemple pour fiche élève) -->
                                        <div class="modal fade" id="ficheEleveModal{{$e->id}}" tabindex="-1" aria-labelledby="ficheEleveModalLabel{{$e->id}}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="ficheEleveModalLabel{{$e->id}}">Fiche de l'élève {{$e->user->prenom}} {{$e->user->nom}}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        
                                                        <!-- Contenu de la fiche élève -->
                                                        <p>INE: {{$e->ine}}</p>
                                                        <p>Date de naissance: {{ \Carbon\Carbon::parse($e->user->date_naissance)->format('d-m-Y à H:i:s') }}</p>

                                                        <p>Classe: {{$classe->nom}}</p>
                                                        <p>Filière: {{$classe->filiere->nom}}</p>

                                                        @if(isset($e->examens) && $e->examens->count() > 0)
                                                        @foreach($e->examens as $examen)
                                                          
                                                                <p class="bg-light">Examen: #{{$examen->id}}  {{$examen->name}} ({{$examen->matiere->nom}})</p>
                                                              
                                                            
                                                            <p>Date {{ \Carbon\Carbon::parse($examen->date_examen)->format('d-m-Y à H:i') }}</p>
                                                                <p class="bg-light">Note:  {{$examen->pivot->note ?? 'Pas encore de note'}}</p>
                                                                
                                                            
                                                        @endforeach
                                                    @else
                                                        <p>
                                                            <td colspan="2" class="text-muted text-center">Aucun examen inscrit</td>
                                                        </p>
                                                    @endif
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Fin du modal -->
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                @endforeach
                
    
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir retirer ces élèves ?');">
                        Retirer
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        // Fonction pour activer/désactiver les élèves lorsque la classe est sélectionnée
        function toggleEleves(classeCheckboxId, elevesListId) {
            var classeCheckbox = document.getElementById(classeCheckboxId);
            var elevesList = document.getElementById(elevesListId);
    
            // Activer/Désactiver tous les checkboxes des élèves associés à cette classe
            var checkboxes = elevesList.querySelectorAll('input[type="checkbox"]');
            checkboxes.forEach(function(checkbox) {
                checkbox.disabled = !classeCheckbox.checked;
            });
        }
    
        // Fonction pour vérifier si une classe a des élèves sélectionnés
        function checkClasse(classeCheckboxId, elevesListId) {
            var classeCheckbox = document.getElementById(classeCheckboxId);
            var elevesList = document.getElementById(elevesListId);
    
            // Activer/Désactiver le checkbox de la classe en fonction de la sélection des élèves
            var checkboxes = elevesList.querySelectorAll('input[type="checkbox"]');
            var allChecked = Array.from(checkboxes).every(function(checkbox) {
                return checkbox.checked;
            });
    
            // Si tous les élèves sont sélectionnés, la case de la classe est cochée
            classeCheckbox.checked = allChecked;
        }
    </script>
    
    </div>
    


        <!-- Associer des élèves -->
        <div class="tab-pane fade" id="add-students" role="tabpanel" aria-labelledby="add-students-tab">
            <h4 class="text-center text-dark mb-4">Associer des élèves à l'examen</h4>

            <form action="{{ route('associer_examen') }}" method="POST">
                @csrf
                <input type='hidden' name='examen_id' value='{{$examen->id}}'> <!-- Ensure this is correct -->
            
                @foreach($professeur->classes as $p)
            
                    @php
                        
                        $elevesInscrits = $examen->eleves->where('classe_id', $p->id);
                    @endphp
                    
                    @if($p->eleves->count() > 0 && $elevesInscrits->count() < $p->eleves->count())
                        <div class="class-card mb-4 p-3 rounded shadow-sm">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="classe_{{$p->id}}" name="classes[]" value="{{$p->id}}" onchange="toggleEleves('classe_{{$p->id}}', 'eleves_classe_{{$p->id}}')">
                                <label class="form-check-label fw-bold" for="classe_{{$p->id}}">
                                    #{{$p->id}} {{$p->nom}} - Filière : {{$p->filiere->nom}} <br>
                                </label>
                            </div>
            
                            <div class="eleves-list ms-3" id="eleves_classe_{{$p->id}}">
                                <table class="table table-sm table-bordered mt-3">
                                    <thead>
                                        <tr>
                                            <th>Sélectionner</th>
                                            <th>Ine</th>
                                            <th>Prénom</th>
                                            <th>Nom</th>
                                            <th>Fiche</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($p->eleves as $e)
                                            @if(!$examen->eleves->contains($e->id))
                                                <tr>
                                                    <td>
                                                        <input class="form-check-input" type="checkbox" name="eleves[]" value="{{$e->id}}" 
                                                        onchange="checkClasse('classe_{{$p->id}}', 'eleves_classe_{{$p->id}}')">
                                                    </td>
                                                    <td>{{$e->ine}}</td>
                                                    <td>{{$e->user->prenom}}</td>
                                                    <td>{{$e->user->nom}}</td>
                                                    <td>
                                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                            Voir
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endif    
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif


                @endforeach
                @foreach($professeur->classeOptions as $co)
    @php
        $elevesInscrits = $examen->eleves->where('classe_option_id', $co->id);
    @endphp

    @if($co->eleves->count() > 0 && $elevesInscrits->count() < $co->eleves->count())
        <div class="class-card mb-4 p-3 rounded shadow-sm">
            <h4> Liste de vos classes options</h4>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="classe_option_{{$co->id}}" name="classe_options[]" value="{{$co->id}}" onchange="toggleEleves('classe_option_{{$co->id}}', 'eleves_classe_option_{{$co->id}}')">
                <label class="form-check-label fw-bold" for="classe_option_{{$co->id}}">
                    #{{$co->id}} {{$co->nom}} <br>
                </label>
            </div>

            <div class="eleves-list ms-3" id="eleves_classe_option_{{$co->id}}">
                <table class="table table-sm table-bordered mt-3">
                    <thead>
                        <tr>
                            <th>Sélectionner</th>
                            <th>Ine</th>
                            <th>Prénom</th>
                            <th>Nom</th>
                            <th>Fiche</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($co->eleves as $e)
                            @if(!$examen->eleves->contains($e->id))
                                <tr>
                                    <td>
                                        <input class="form-check-input" type="checkbox" name="eleves[]" value="{{$e->id}}" 
                                        onchange="checkClasse('classe_option_{{$co->id}}', 'eleves_classe_option_{{$co->id}}')">
                                    </td>
                                    <td>{{$e->ine}}</td>
                                    <td>{{$e->user->prenom}}</td>
                                    <td>{{$e->user->nom}}</td>
                                    <td>
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                            Voir
                                        </button>
                                    </td>
                                </tr>
                            @endif    
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endforeach

            
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-success btn-lg">
                         Inscrire
                    </button>
                </div>
            </form>
            
        </div>

        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content shadow-lg rounded-3">
                    
                    <!-- En-tête du modal -->
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="exampleModalLabel">
                            Détails de l'élève 
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    
                    <!-- Corps du modal -->
                    <div class="modal-body">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th class="bg-light">Id</th>
                                    <td>{{$e->id}}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">INE</th>
                                    <td>{{$e->ine}}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Prénom</th>
                                    <td>{{$e->user->prenom}}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Nom</th>
                                    <td>{{$e->user->nom}}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Date de naissance</th>
                                    <td>{{ \Carbon\Carbon::parse($e->user->date_naissance)->format('d-m-Y à H:i') }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Classe</th>
                                    <td>{{$e->classe->nom}}</td>
                                </tr>
                                <tr>
                                    <th class="bg-light">Filière</th>
                                    <td>{{$e->classe->filiere->nom}}</td>
                                </tr>
                                
                                <!-- Titre des examens -->
                                <tr>
                                    <th class="bg-light" colspan="2" class="text-center">Examens inscrits</th>
                                </tr>
                                
                                @if(isset($e->examens) && $e->examens->count() > 0)
                                
                                    @foreach($e->examens as $examen)
                                        <tr>
                                            <th class="bg-light">Examen</th>
                                            <td>#{{$examen->id}} {{$examen->name}} ({{$examen->matiere->nom}})</td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light">Date</th>
                                            <td>{{ \Carbon\Carbon::parse($examen->date_examen)->format('d-m-Y à H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light">Note</th>
                                            <td>{{$examen->note ?? 'Pas encore de note'}}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="2" class="text-muted text-center">Aucun examen inscrit</td>
                                    </tr>
                                @endif
                            </tbody>
                            
                        </table>
                    </div>
                    
                    <!-- Pied du modal -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Fermer
                        </button>
                    </div>
            
                </div>
            </div>
            
        </div>

        </div>
        </div>

<!-- JavaScript pour gérer le comportement dynamique des cases à cocher -->
<script>
    function toggleEleves(classeId, elevesId) {
        const classeCheckbox = document.getElementById(classeId);
        const elevesCheckboxes = document.querySelectorAll(`#${elevesId} .form-check-input`);

        elevesCheckboxes.forEach(checkbox => {
            checkbox.checked = classeCheckbox.checked;
        });
    }

    function checkClasse(classeId, elevesId) {
        const classeCheckbox = document.getElementById(classeId);
        const elevesCheckboxes = document.querySelectorAll(`#${elevesId} .form-check-input`);

        // Si tous les élèves sont sélectionnés, coche la case de la classe
        if (![...elevesCheckboxes].some(checkbox => !checkbox.checked)) {
            classeCheckbox.checked = true;
        } else {
            // Si au moins un élève est décoché, décoche la classe
            classeCheckbox.checked = false;
        }
    }
</script>

@endsection


