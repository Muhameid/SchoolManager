@extends('layouts.templateAdmin')

@section('content')
<form class="p-4 bg-white rounded shadow" action="/enregistrer_classe_option" method="POST">
    @csrf
    <div class="form-group mb-3">
        <label for="autoSizingInput">Nom de la classe option</label>
        <input type="text" class="form-control" name="nom" maxlength="200" min="2" id="autoSizingInput" placeholder="Nom de la Classe">
    </div>

    <div class="form-group mb-3">
        <select class="form-select" aria-label="Sélectionner la matière" name="matiere_id" id="matiereSelect">
            
            @foreach ($result as $item)
                <option value="{{ $item->id }}" class="text-center">{{ $item->nom }}</option>
            @endforeach
        </select>
    </div>

    

    <div class="form-group mb-3">
        <button type="submit" class="btn btn-primary">Enregistrer</button>
    </div>
</form>



<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

@endsection
