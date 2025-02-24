@extends('layouts.template')
@section('content')
<form class="p-4 bg-white rounded shadow" action="/enregistrer_tenant" method="POST">
    @csrf
    <div class="form-group mb-3">
        <label class=" "  for="autoSizingInput">Id du tenant</label>
      <input type="text" class="form-control" name="id" maxlength="200"  min="2" id="autoSizingInput" placeholder="Id du tenant">
    </div>
    <div class="form-group mb-3">
        <label class=" "  for="autoSizingInput">Nom de l'école</label>
      <input type="text" class="form-control" name="nom" maxlength="200"  min="2" id="autoSizingInput" placeholder="Nom de l'école">
    </div>
    <div class="form-group mb-3">
        <label class=" " for="autoSizingInputGroup">E-mail</label>
      <div class="input-group">
        <div class="input-group-text">@</div>
        <input type="text" class="form-control" maxlength="255"  min="5" name="email" id="autoSizingInputGroup" placeholder="E-mail">
      </div>
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