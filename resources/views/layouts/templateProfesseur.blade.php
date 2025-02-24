<!DOCTYPE html>
<html lang="fr">
<head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>France Academy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

</head>
<style>
    .navbar-nav .nav-link {
        font-family:inherit;
        font-weight: 500;
        font-size: 16px;
    }

    .navbar-nav .nav-link:hover {
        color: #f8f9fa;
    }

    .navbar h6 {
        font-family: 'Roboto', sans-serif;
        font-weight: 400;
    }
</style>

<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="/dashboard_eleve">
            <img src="{{global_asset('dist')}}/images/LOGO.png" alt="FranceAcademy Logo" height="60">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item mx-2">
                    <a class="nav-link text-white" href="/dashboard_professeur">Accueil</a>
                </li>
                <li class="nav-item mx-2">
                    <a class="nav-link text-white" href="/listeClassesProf">Mes classes</a>
                </li>
               
                <li class="nav-item mx-2">
                    <a class="nav-link text-white" href="/liste_examens">Liste des Examens</a>
                </li>
            </ul>
            <h6 class="p-2 text-white">
                @if(is_object(Auth::user()))
                    {{ Auth::user()->name }}
                @endif
            </h6>
            <form class="d-flex" action="/logout" method="get">
                <button class="btn btn-outline-light" type="submit">
                    <i class="bi bi-box-arrow-right"></i> Déconnecter&nbsp;{{ Auth::user()->prenom }}&nbsp;{{ Auth::user()->nom }}
                </button>
            </form>
        </div>
    </div>
</nav>

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

@if ($errors->any())
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@yield('content')


<script>
    $(document).ready(function() {
        // Appliquer DataTables au tableau avec l'ID 'tenantTable'
        $('#tenantTable').DataTable();
    });
</script>

<!-- JS de Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>