@extends('layouts.templateAdmin')

@section('content')
<form class="p-4 bg-white rounded shadow" action="/enregistrer_classe" method="POST">
    @csrf
    <div class="form-group mb-3">
        <label class=" " for="autoSizingInput">Nom de la classe</label>
        <input type="text" class="form-control" name="nom" maxlength="200" min="2" id="autoSizingInput" placeholder="Nom de la Classe">
    </div>

    <div class="form-group mb-3">
        <select class="form-select" aria-label="Default select example" name="filiere_id">
            <option selected>Sélectionner la filiére</option>

            @foreach ($result as $item)
                <option value="{{ $item->id }}" class="text-center">{{ $item->nom }}</option>
            @endforeach
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
@endsection