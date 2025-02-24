@extends('layouts.templateAdmin')

@section('content')
<form class="p-4 bg-white rounded shadow" action="/enregistrer_filiere" method="POST">
    @csrf
    <div class="form-group mb-3">
        <label class=" " for="filiereNom">Nom de la filière</label>
        <input type="text" class="form-control @error('nom') is-invalid @enderror" name="nom" maxlength="50" minlength="2" id="filiereNom" placeholder="Nom de la filière" value="{{ old('nom') }}" required>
        @error('nom')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group mb-3">
        <label class=" " for="filiereNiveau">Niveau de la filière</label>
        <input type="number" class="form-control @error('niveau') is-invalid @enderror" name="niveau" min="1" max="5" id="filiereNiveau" placeholder="Niveau (ex: 1)" value="{{ old('niveau') }}" required>
        @error('niveau')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group mb-3">
        <label class=" " for="filiereDescription">Description</label>
        <textarea class="form-control @error('description') is-invalid @enderror" name="description" id="filiereDescription" rows="3" placeholder="Description de la filière" required>{{ old('description') }}</textarea>
        @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group mb-3">
        <button type="submit" class="btn btn-primary">Enregistrer</button>
    </div>
</form>

@endsection
