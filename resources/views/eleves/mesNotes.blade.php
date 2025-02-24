@extends('layouts.templateEleve')

@section('content')
<style>
@import url('https://fonts.googleapis.com/css2?family=Quicksand:wght@400;600&display=swap');

body {
    font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
    background-color: #f4f6f9;
    margin: 0;
    padding: 0;
}

.container {
    max-width: 1000px;
    margin: auto;
}

.flex-container {
    display: flex;
    justify-content: space-between;
    gap: 30px;
    margin-bottom: 30px;
}

.moyenne-filiere {
    background: #333;
    color: white;
    padding: 20px;
    border-radius: 15px;
    text-align: center;
    font-size: 1.7rem;
    font-weight: bold;
    width: 48%;
    box-shadow: 0px 6px 18px rgba(0, 0, 0, 0.15);
    transition: transform 0.3s ease-in-out;
}

.moyenne-filiere:hover {
    transform: scale(1.05);
}

.moyenne-filiere span {
    font-size: 2rem;
}

.matiere-block {
    background: white;
    padding: 25px;
    margin: 20px 0;
    border-radius: 15px;
    box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease-in-out;
}

.matiere-block:hover {
    transform: translateY(-8px);
}

.card-header {
    background: #a91111;
    color: white;
    font-weight: bold;
    padding: 15px;
    border-radius: 15px;
    margin-top: 8px;
    text-align: center;
}

.moyenne-details {
    color: #333;
    text-align: center;
    padding: 10px;
    font-size: 1rem;
    border-radius: 15px;
    font-weight: bold;
    margin-top: 5px;
    display: block;
}

.list-group-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px;
    border-radius: 10px;
    font-size: 16px;
    background-color: #f9f9f9;
    transition: background-color 0.3s ease;
}

.list-group-item:hover {
    background-color: #f1f1f1;
}

.badge-success {
    background: #ffffff;
    color: rgb(11, 11, 11);
    font-size: 18px;
    padding: 8px 12px;
    border-radius: 15px;
    font-weight: bold;
}

h2, h4 {
    text-transform: uppercase;
    font-weight: bold;
    color: #333;
    text-align: center;
    margin-bottom: 30px;
}

.form-check {
    display: block;
    align-items: center;
    margin-top: 5px;
}

.form-check-input {
    margin-right: 10px;
}

.moyenne-filiere-examen {
    margin-top: 5px;
    font-size: 0.8rem;
    color: #555;
    line-height: 1.4;
}

/* Style par défaut (désactivé = gris) */
.form-check-input {
    background-color: #c2c2c2;
    border-color: #c2c2c2;
}

/* Quand activé (rouge) */
.form-check-input:checked {
    background-color: #a91111;
    border-color: #a91111;
}

.form-check-input:checked:focus {
    box-shadow: 0 0 0 0.25rem rgba(90, 124, 160, 0.25);
}

.note-details-container {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
}
</style>

<div class="container mt-5">
    <div class="flex-container">
        <div class="moyenne-filiere">
            <h5>Moyenne Générale :</h5>
            <span>{{ ($moyenneGenerale['moyenne_finale'] ?? 0) == 0 ? 'N/A' : number_format($moyenneGenerale['moyenne_finale'], 2) }}</span>
        </div>

        <div class="moyenne-filiere">
            <h5 class="mb-0">Moyenne Générale avec option :</h5>
            <span>{{ isset($moyenneGeneraleAvecOption) ? number_format($moyenneGeneraleAvecOption, 2) : 'N/A' }}</span>
        </div>
    </div>


    <h2 class="mb-4">Mes Notes</h2>
    @foreach($notes as $optionNom => $examens)
        <div class="matiere-block">
            <div class="card-header">
                <h5>{{ $optionNom }}</h5>
                Moyenne : {{ ($moyennesMatiere[$optionNom] ?? 0) == 0 ? 'N/A' : number_format($moyennesMatiere[$optionNom], 2) }}
            </div>
            <div class="card-body">
                @if($examens->isEmpty())
                    <p class="text-center mt-3">Aucun examen disponible.</p>
                @else
                    <ul class="list-group mt-3">
                        @foreach($examens as $examen)
                            <li class="list-group-item">
                                <div>
                                    <strong>{{ $examen->name }} - {{ $examen->sujet }}</strong><br>
                                    <small>Coefficient : {{ $examen->coefficient }}</small>
                                </div>
                                <div class="note-details-container">
                                    <span class="badge badge-success">{{ $examen->pivot->note ?? 'N/A' }}/20</span>

                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="switch-{{ $examen->id }}" onchange="toggleMoyenne({{ $examen->id }})">
                                        <label class="form-check-label" for="switch-{{ $examen->id }}">Détails de l'examen</label>
                                    </div>

                                    <div class="moyenne-filiere-examen" id="moyenne-{{ $examen->id }}" style="display: none;">
                                        @if(!empty($moyenneExamensFiliere) && isset($moyenneExamensFiliere[$optionNom]))
                                            @foreach($moyenneExamensFiliere[$optionNom] as $examenDetails)
                                                @if($examenDetails['id'] == $examen->id)
                                                    Moyenne de la classe : {{ $examenDetails['moyenne'] }}/20<br>
                                                    Note Max : {{ $examenDetails['max'] }}/20<br>
                                                    Note Min : {{ $examenDetails['min'] }}/20
                                                @endif
                                            @endforeach
                                        @else
                                            Aucun examen trouvé.
                                        @endif
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    @endforeach

    <tr>
        <td colspan="100%" style="border-top: 2px solid #333;"></td>
    </tr>

    <h2 class="text-center my-5">Options</h2>
    <tr>
        <td colspan="100%" style="border-bottom: 2px solid #333;"></td>
    </tr>
    @foreach($notesOption as $optionNom => $examens)
        <div class="matiere-block">
            <div class="card-header">
                <h5 class="mb-0">{{ $optionNom ?? 'Aucune Option' }}</h5>
                Moyenne : {{ ($moyennesMatiere[$optionNom] ?? 0) == 0 ? 'N/A' : number_format($moyennesMatiere[$optionNom], 2) }}
            </div>
            <div class="card-body">
                @if(is_array($examens) || $examens instanceof \Illuminate\Support\Collection)
                    @if($examens->isEmpty())
                        <p class="text-center mt-3">Aucun examen disponible.</p>
                    @else
                        <ul class="list-group mt-3">
                            @foreach($examens as $examen)
                                <li class="list-group-item">
                                    <div>
                                        <strong>{{ $examen->name }} - {{ $examen->sujet }}</strong><br>
                                        <small>Coefficient : {{ $examen->coefficient }}</small>
                                    </div>
                                    <div class="note-details-container">
                                        <span class="badge badge-success">{{ $examen->pivot->note ?? 'N/A' }}/20</span>

                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckOption{{ $examen->id }}" onchange="toggleMoyenneOption({{ $examen->id }})">
                                            <label class="form-check-label" for="flexSwitchCheckOption{{ $examen->id }}">Détails de l'examen</label>
                                        </div>

                                        <div class="moyenne-filiere-examen" id="moyenne-option-{{ $examen->id }}" style="display: none;">
                                            @if(!empty($moyenneExamensOption) && isset($moyenneExamensOption[$optionNom]))
                                                @foreach($moyenneExamensOption[$optionNom] as $examenDetails)
                                                    @if($examenDetails['id'] == $examen->id)
                                                        Moyenne de la classe : {{ $examenDetails['moyenne'] }}/20<br>
                                                        Note max : {{ $examenDetails['max'] }}/20<br>
                                                        Note min : {{ $examenDetails['min'] }}/20
                                                    @endif
                                                @endforeach
                                            @else
                                                Aucun examen trouvé.
                                            @endif
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                @else
                    <p class="text-center mt-3">Non inscrit dans une option.</p>
                @endif
            </div>
        </div>
    @endforeach

</div>

<script>
function toggleMoyenne(examenId) {
    let moyenneDiv = document.getElementById('moyenne-' + examenId);
    let switchInput = document.getElementById('switch-' + examenId);
    moyenneDiv.style.display = switchInput.checked ? 'block' : 'none';
}

function toggleMoyenneOption(examenId) {
    let moyenneDiv = document.getElementById('moyenne-option-' + examenId);
    let switchInput = document.getElementById('flexSwitchCheckOption' + examenId);


    moyenneDiv.style.display = switchInput.checked ? 'block' : 'none';
}


document.addEventListener('DOMContentLoaded', function() {
    @foreach($notes as $optionNom => $examens)
        @foreach($examens as $examen)
            document.getElementById('moyenne-{{ $examen->id }}').style.display = 'none';
        @endforeach
    @endforeach

    @foreach($notesOption as $optionNom => $examens)
        @if(is_array($examens) || $examens instanceof \Illuminate\Support\Collection)
            @foreach($examens as $examen)
                document.getElementById('moyenne-option-{{ $examen->id }}').style.display = 'none';
            @endforeach
        @endif
    @endforeach
});
</script>

@endsection