@extends('layouts.templateProfesseur')

@section('content')
<div class="container mt-5">
    <h2 class="text-center mb-4">Créer un Examen</h2>

 
    <form method="POST" action="{{ route('enregistrement_examen') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="professeur_id" value="{{ Auth::user()->usereable()->first()->id }}">

     
        <div class="mb-3">
            <label for="matiere_id" class="form-label fs-5 fw-semibold">Sélectionner une Matière</label>
            <select class="form-select" name="matiere_id" id="matiere_id" required>
                <option value="" disabled selected>Choisir une matière</option>
                @foreach($examens as $matiere)
                    <option value="{{ $matiere->id }}">{{ $matiere->nom }}</option>
                @endforeach
            </select>
        </div>

    
        <div class="mb-3">
            <label for="nomExamen" class="form-label fs-5 fw-semibold">Nom de l'examen</label>
            <input type="text" class="form-control" name="name" required placeholder="Nom de l'examen">
        </div>

        <div class="mb-3">
            <label for="sujetExamen" class="form-label fs-5 fw-semibold">Sujet</label>
            <input type="text" class="form-control" name="sujet" required placeholder="Sujet de l'examen">
        </div>

        
        <div class="mb-3">
            <label for="coefficientExamen" class="form-label fs-5 fw-semibold">Coefficient</label>
            <input type="number" class="form-control" name="coefficient" required min="1" placeholder="Coefficient de l'examen">
        </div>

     
        <div class="mb-3">
            <label for="dateExamen" class="form-label fs-5 fw-semibold">Date de l'examen</label>
            <input type="date" class="form-control" name="date_examen" required>
        </div>

      
        <div class="mb-3">
            <label for="lienExamen" class="form-label fs-5 fw-semibold">Lien (facultatif)</label>
            <input type="file" class="form-control" name="lien" id="lien" placeholder="Lien de l'examen (facultatif)">
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-primary">Créer l'examen</button>
        </div>
    </form>
</div>


<style>
    .form-label {
        font-weight: bold;
    }

    .form-select, .form-control {
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .fs-5 {
        font-size: 1.1rem;
    }

    .text-center {
        text-align: center;
    }
</style>

<!-- Scripts Bootstrap 5 -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

@endsection
