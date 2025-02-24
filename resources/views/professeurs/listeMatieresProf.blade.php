@extends('layouts.templateProfesseur')

@section('content')

<div class="p-4">
    <h5 style="text-align:center">Choisiser la matiere à noter</h5>
  <table class="table table-striped" id="tenantTable">
    <thead>
        <tr>
          <th scope="col" class="text-center">#</th>
          <th scope="col" class="text-center">Nom de l'élève</th>
          <th scope="col" class="text-center">Prénom de l'élève</th>
          <th scope="col" class="text-center">Examen</th>
          
        </tr>
    </thead>
    <tbody>
        @php
        $i=1;
        @endphp
        @foreach ($classes as $item)
        
      
    <tr>
        <th scope="row">{{$item->id}}</th>
        <td class="text-center">{{$item->nom}}</td>
        <td class="text-center">{{$item->description}}</td>
        <td class="text-center"><a type="button" class="btn btn-primary" href='/listeExamenProf/{{$item->id}}'>Noter</a></td>
    </tr>
        @php
        $i++;
        @endphp
       @endforeach
        
    </tbody>
</table>
</div>
@endsection     