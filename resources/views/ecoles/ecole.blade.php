@extends('layouts.template')

@section('content')
<div class=" p-4 mr-2">
    <a type="button" class="btn btn-light border" href="/ajout_tenant">Ajouter une nouvelle école</a>
</div>

<div class="p-4">
  <table class="table table-striped" id="tenantTable">
    <thead>
        <tr>
          <th scope="col" class="text-center">#</th>
          <th scope="col" class="text-center">id</th>
          <th scope="col" class="text-center">Nom</th>
          <th scope="col" class="text-center">Email</th>
          <th scope="col" class="text-center">Domaines</th>
          <th scope="col" class="text-center">Actions</th>
        </tr>
    </thead>
    <tbody>
        @php
            $i=1;
        @endphp
        @foreach ($result as $item)
        <tr>
            <th scope="row">{{$i}}</th>
            <td class="text-center">{{$item->id}}</td>
            <td class="text-center">{{$item->nom}}</td>
            <td class="text-center">{{$item->email}}</td>
            <td class="text-center"> 
                 @if(count($item->domains) > 0)
                    {{$item->domains[0]->domain}}
                @else
                    Aucune donnée
                @endif</td>
            <td class="text-center"><a type="button" class="btn btn-danger" href='/supprimer_tenant/{{$item->id}}'>Supprimer</a></td>
        </tr>
        @php
            $i++;
        @endphp
        @endforeach
    </tbody>
</table>
</div>


@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

<script>
    $(document).ready(function() {
        // Appliquer DataTables au tableau avec l'ID 'tenantTable'
        $('#tenantTable').DataTable();
    });
</script>

@endsection
