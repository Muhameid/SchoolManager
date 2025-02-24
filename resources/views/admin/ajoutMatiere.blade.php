@extends('layouts.templateAdmin')
@section('content')
<form class="p-4 bg-white rounded shadow" action="/enregistrementMatiere" method="POST">
    @csrf
    <div class="form-group">
      <label for="nom">Nom de la matière</label>
      <input type="text" class="form-control" id="nom" name="nom" required>
  </div>

  <div class="form-group">
      <label for="description">Description</label>
      <textarea class="form-control" id="description" name="description" required></textarea>
      <br>
  </div>

  <div class="form-group mb-3">
    <button type="submit" class="btn btn-primary">Enregistrer la matiere</button>
</div>
</form>


@endsection