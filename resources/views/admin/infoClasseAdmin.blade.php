@extends('layouts.templateAdmin')

@section('content')

@php
    $toto = [];
    foreach ($classe->professeurs as $professeur) {
        $toto[$professeur->pivot->matiere_id] = $professeur;
    }
@endphp


<div class="container mt-4">
    <h1 class="fw-bold text-dark">
        <i class="bi bi-building"></i> Classe : {{$classe->nom}} <span class="text-muted">#{{$classe->id}}</span>
    </h1>
    <h2 class="text-secondary">
        <i class="bi bi-mortarboard"></i> Filière : {{$classe->filiere->nom}} 
        (Niveau {{$classe->filiere->niveau}}) <span class="text-muted">#{{$classe->filiere->id}}</span>
    </h2>

    <ul class="nav nav-tabs mt-3" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="matieres-tab" data-bs-toggle="tab" href="#matieres" role="tab" aria-controls="matieres" aria-selected="true">
                <i class="bi bi-book"></i> Matières
            </a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="classes-tab" data-bs-toggle="tab" href="#classes" role="tab" aria-controls="classes" aria-selected="false">
                <i class="bi bi-people"></i> Élèves
            </a>
        </li>
    </ul>
</div>


<div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="matieres" role="tabpanel" aria-labelledby="matieres-tab">
        <table id="matieres-table" class="table mt-3">
            <thead>
                <tr>
                    <th>Matière</th>
                    <th>Description</th>
                    <th>Coefficient</th>
                    <th>Professeur</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                
                @foreach ($classe->filiere->matieres as $matiere)
                    <tr>
                        <td>{{ $matiere->nom }} #{{$matiere->id }}</td>
                        <td>
                            <textarea class="form-control-plaintext w-100 p-2 bg-light" readonly
                                data-bs-toggle="tooltip" data-bs-placement="top" 
                                title="{{ $matiere->description }}">{{ $matiere->description }}</textarea>
                        </td>
                                                <td>{{ $matiere->pivot->coefficient }}</td>
                        <td>
                            @if(isset($toto[$matiere->id]))   
                                {{ $toto[$matiere->id]->user->nom }} {{ $toto[$matiere->id]->user->prenom }} #{{ $toto[$matiere->id]->user->id }}
                            @else
                                <span class="text-muted">Aucun professeur affecté</span>
                            @endif
                        </td>
                        <td>
                            @if(isset($toto[$matiere->id]))   
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modal_suppr_prof_{{$matiere->id}}">
                                    Supprimer ce professeur
                                </button>
                            @else
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_prof_matiere_{{$matiere->id}}">
                                    Ajouter un professeur
                                </button>
                            @endif
                        </td>
                    </tr>

                    <div class="modal fade" id="modal_prof_matiere_{{$matiere->id}}" tabindex="-1" aria-labelledby="modalProfLabel{{$matiere->id}}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Professeurs habilités pour {{$matiere->nom}} #{{$matiere->id}}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form method="POST" action="{{ route('assigner_professeur') }}">
                                        @csrf
                                        <input name="matiere_id" value="{{$matiere->id}}" type="hidden"> 
                                        <input name="id_classe" value="{{$classe->id}}" type="hidden"> 
                                        <div class="form-check">
                                            @php $profTrouve = false; @endphp
                                            @foreach($matiere->liste_prof as $professeurHab)
                                                <div>
                                                    @php
                                                        if ($profTrouve) echo '<hr>';
                                                        $profTrouve = true;
                                                    @endphp
                                                    <input class="form-check-input" type="radio" name="professeur_id" value="{{ $professeurHab->id }}" id="prof_{{ $professeurHab->id }}">
                                                    <label class="form-check-label" for="prof_{{ $professeurHab->id }}">
                                                        {{ $professeurHab->user->nom }} {{ $professeurHab->user->prenom }} #{{ $professeurHab->id }}
                                                    </label>
                                                </div>
                                            @endforeach

                                            <!-- Affichage du bouton "Enregistrer" seulement si aucun professeur n'est affecté -->
                                            @if(!$profTrouve)
                                              
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                                </div>
                                            @else
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                                <button type="submit" class="btn btn-primary">Enregistrer</button>
                                            </div>
                                            @endif

                                            @if (!$profTrouve)
                                                <p>Pas de professeur dans cette école — Veuillez recruter un professeur.</p>
                                            @endif
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if(isset($toto[$matiere->id]))  
                    <div class="modal fade" id="modal_suppr_prof_{{$matiere->id}}" tabindex="-1" aria-labelledby="modalSupprLabel{{$matiere->id}}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Confirmer la suppression</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Êtes-vous sûr de vouloir retirer le professeur <strong>{{ $toto[$matiere->id]->user->nom }}</strong> de la matière <strong>{{ $matiere->nom }}</strong> ?</p>
                                </div>
                                <div class="modal-footer">
                                    <form method="POST" action="{{ route('assignerProfesseurDelete') }}">
                                        @csrf
                                        <input type="hidden" name="matiere_id" value="{{ $matiere->id }}">
                                        <input type="hidden" name="id_classe" value="{{ $classe->id }}">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                        <button type="submit" class="btn btn-danger">Confirmer</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                @endforeach
            </tbody>
        </table>
    </div>

    <div class="tab-pane fade" id="classes" role="tabpanel" aria-labelledby="classes-tab">
        <h3>Liste des élèves de la classe</h3>
        <table id="eleves-table" class="table mt-3">
            <thead>
                <tr>
                    <th>INE</th>
                    <th>Prénom</th>
                    <th>Nom</th>
                    <th>Date de naissance</th>
                    <th>Retirer élève</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($classe->eleves as $eleve)
                    <tr>
                        <td>{{ $eleve->ine }}</td>
                        <td>{{ $eleve->user ? $eleve->user->prenom : 'Utilisateur non trouvé' }}</td>
                        <td>{{ $eleve->user ? $eleve->user->nom : 'Nom non disponible' }}</td>
                        <td>{{ $eleve->user ? date('d-m-Y', strtotime($eleve->user->date_naissance)) : '' }}</td>
                        <td>
                            <a type="button" class="btn btn-danger btn-sm" href="{{ route('retirer_classe', $eleve->id) }}" onclick="return confirm('Êtes-vous sûr de vouloir retirer cet élève ?');">
                                Retirer
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#matieres-table').DataTable({
            "order": [[0, 'asc']], 
            "columnDefs": [
                { "targets": [0], "orderable": false }
            ]
        });

        $('#eleves-table').DataTable();
    });
</script>

@endsection
