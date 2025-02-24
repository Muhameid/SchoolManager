@extends('layouts.templateAdmin')

@section('content')

<div class="container mt-4">
    <!-- Titre et bouton d'ajout -->
    <div class="d-flex flex-column align-items-center mb-4">
        <h1 class="fw-bold text-center">Gérer vos classes options</h1>
        <a class="btn btn-success mt-3" href="/ajoutClasseOptionAdmin">
            <i class="fas fa-plus-circle"></i> Ajouter une nouvelle classe option
        </a>
    </div>

    <!-- Contenu principal dans une boîte -->
    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-bordered table-hover" id="tenantTable">
                <thead class="table-dark">
                    <tr>
                        <th scope="col" class="text-center">#</th>
                        <th scope="col" class="text-center">Nom</th>
                        <th scope="col" class="text-center">Coefficient</th>
                        <th scope="col" class="text-center">Matière</th>
                        <th scope="col" class="text-center">Professeur</th>
                        <th scope="col" class="text-center">Élèves</th>
                        <th scope="col" class="text-center">Moyenne</th>
                        <th scope="col" class="text-center">Liste des notes et examens</th>
                        <th scope="col" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($result['classes'] as $item)
                        <tr>
                            <th scope="row" class="text-center">{{ $item->id }}</th>
                            <td class="text-center">{{ $item->nom }}</td>
                            <td class="text-center">{{ $item->coefficient  }}</td>
                            <td class="text-center">{{ $item->matiere->nom}}</td>

                            <td class="text-center">
                                @if($item->professeur)
                                    <a class="text-decoration-none" data-toggle="modal" data-target="#assignProfModal{{ $item->id }}">
                                        👨‍🏫 {{ $item->professeur->user->nom }} {{ $item->professeur->user->prenom }} #{{ $item->professeur->id }}
                                    </a>
                                @else
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#assignProfModal{{ $item->id }}">
                                        Assigner à un professeur
                                    </button>
                                @endif
                                <!-- Modal -->
                                <div class="modal fade" id="assignProfModal{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="assignProfModalLabel{{ $item->id }}" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="assignProfModalLabel{{ $item->id }}">
                                                    Professeurs habilités pour la classe : {{ $item->nom  }}#{{ $item->id  }}
                                                </h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>

                                            <div class="modal-body">
                                                <p>Voici la liste des professeurs habilités pour la matière ({{$item->matiere->professeurs->count()}}) : <strong>{{ $item->matiere->nom }}</strong></p>

                                                @if(isset($result['professeursHabilitesParMatiere'][$item->matiere->id]))
                                                    <form action="{{ route('assigner_professeur_option') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="matiere_id" value="{{ $item->matiere->id }}">
                                                        <input type="hidden" name="classe_option_id" value="{{ $item->id }}">
                                                        <select  name='professeur_id'>
                                                            <option value=''>Aucun prof</option>
                                                            @foreach($item->matiere->professeurs as $professeurHabil)
                                                                <option @if(is_object($item->professeur) && $professeurHabil->id == $item->professeur->id) selected @endif value='{{$professeurHabil->id}}'>
                                                                    {{$professeurHabil->user->nom}} {{$professeurHabil->user->prenom}} #{{$professeurHabil->id}}
                                                                </option>
                                                            @endforeach        
                                                        </select>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                                                            <button type="submit" class="btn btn-primary">Assigner</button>
                                                        </div>
                                                    </form>
                                                @else
                                                    <p>Aucun professeur habilité pour cette matière.</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td class="text-center">{{ $item->eleves->count() }}</td>
                            <td class="text-center">??</td>
                            <td class="text-center">??</td>                            <td class="text-center">
                                <a type="button" class="btn btn-primary btn-sm" href='/info_classe_option/{{ $item->id }}'>
                                    👀 Voir élèves
                                </a>
                                @if($item->supprimableOption())
                                <a type="button" class="btn btn-danger btn-sm" href='/supprimerClasseOption/{{ $item->id }}' onclick="return confirm('❌ Voulez-vous vraiment supprimer cette classe ?');">
                                    🗑 Supprimer
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

<!-- jQuery -->
<!-- Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

@endsection
