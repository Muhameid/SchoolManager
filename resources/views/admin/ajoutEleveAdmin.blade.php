@extends('layouts.templateAdmin')

@section('content')
<form class="p-4 bg-white rounded shadow" action="/enregistrer_eleve" method="POST">
    @csrf
    
    <div class="form-group mb-3">
        <label for="nom">Nom de l'élève</label>
        <input type="text" class="form-control" name="nom" maxlength="200" minlength="2" id="nom" placeholder="Nom de l'élève" required>
    </div>
    
    <div class="form-group mb-3">
        <label for="prenom">Prénom de l'élève</label>
        <input type="text" class="form-control" name="prenom" maxlength="200" minlength="2" id="prenom" placeholder="Prénom" required>
    </div>
    
    <div class="form-group mb-3">
        <label for="login">Login</label>
        <input type="text" class="form-control" name="login" maxlength="200" minlength="2" id="login" placeholder="Login" required>
    </div>
    
    <div class="form-group mb-3">
        <label for="date_naissance">Date de naissance</label>
        <input type="date" class="form-control" name="date_naissance" id="date_naissance" required>
    </div>
    
    <div class="form-group mb-3">
        <label for="ine">INE (11 caractères)</label>
        <input type="text" class="form-control" name="ine" maxlength="11" minlength="11" id="ine" placeholder="INE" required>
    </div>
    
    <div class="form-group mb-3">
        <label for="password">Mot de passe temporaire (min. 8 caractères)</label>
        <input type="password" class="form-control" name="password" maxlength="30" minlength="8" id="password" placeholder="Mot de passe" required>
    </div>
    
    <div class="form-group mb-3">
        <label for="telephone_1">Numéro de téléphone (min. 8 caractères)</label>
        <input type="text" class="form-control" name="telephone_1" maxlength="30" minlength="8" id="telephone_1" placeholder="Téléphone" required>
    </div>
    
    <div class="form-group mb-3">
        <label for="adresse">Adresse</label>
        <textarea class="form-control" name="adresse" maxlength="300" minlength="2" id="adresse" placeholder="Adresse" required></textarea>
    </div>
    
    <div class="form-group mb-3">
        <label for="country-dropdown">Pays</label>
        <select id="country-dropdown" class="form-control" name="pays_id" required>
            <option value="">-- Sélectionner un pays --</option>
            @foreach ($pays as $data)
                <option value="{{$data->id}}">{{$data->nom}}</option>
            @endforeach
        </select>
    </div>
    
    <div class="form-group mb-3">
        <label for="city-dropdown">Ville</label>
        <select id="city-dropdown" name="ville_id" class="form-control" required>
            <option value="">-- Sélectionner une ville --</option>
        </select>
    </div>
    
    <div class="form-group mb-3">
        <button type="submit" class="btn btn-primary">Enregistrer</button>
    </div>
</form>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
$(document).ready(function () {
    $('#country-dropdown').on('change', function () {
        var paysId = this.value;
        $("#city-dropdown").html('<option value="">-- Sélectionner une ville --</option>');

        if (paysId) {
            $.ajax({
                url: "{{url('api/fetch-cities')}}",
                type: "POST",
                data: {
                    pays_id: paysId,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function (response) {
                    $.each(response.villes, function (key, value) {
                        $("#city-dropdown").append('<option value="' + value.id + '">' + value.nom + '</option>');
                    });
                },
                error: function () {
                    alert('Une erreur est survenue lors du chargement des villes.');
                }
            });
        }
    });
});
</script>
@endsection
