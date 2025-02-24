@extends('layouts.templateAdmin')
@section('content')
<form class="p-4 bg-white rounded shadow" action="/enregistrer_classe_filiere" method="POST">
    @csrf
    <div class="form-group mb-3">
        
         
      </div>
    
    <div class="form-group mb-3">
      <select class="form-select" aria-label="Default select example" name="filiere_id">
        <option selected>Sélectionner la filiére</option>

        @foreach ($result as $item)
      
        <tr>
          
            
            <option value="{{$item->filieres->id }}" class="text-center">{{$item->filieres->nom }}</option> 
            
        </tr>
    @endforeach
        </select>
       
    </div>
    </div>

    <div class="form-group mb-3">
        <button type="submit" class="btn btn-primary">Enregistrer</button>
    </div>
  </form>

@endif
@endsection