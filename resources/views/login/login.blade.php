<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <title>Document</title>
</head>
<body class="bg-light d-flex justify-content-center align-items-center vh-100">
            {{-- @dd($central); --}}
            <!-- Logo centré en haut -->
            @php
                if($central==true)$text_connexion='Connexion des administrateurs';
                else $text_connexion='Connexion des utilisateurs';
            @endphp
            <div class="position-absolute top-0 start-50 translate-middle-x">
                <img src="{{global_asset('dist')}}/images/LOGO.png" alt="Logo" width="190" height="120">
            </div>
    <div class="position-relative">

        
        <!-- Formulaire -->
        <form action="/authenticate" method="POST" class="p-4 bg-white rounded shadow" style="width: 100%; max-width: 400px;">
            @csrf
            <h4>{{$text_connexion}}</h4>
            <div class="form-group mb-3">
                <label for="login" class="form-label">Login</label>
                <input type="text" name='login' required maxlength="255" class="form-control" id="login" placeholder="Entrer votre login">
            </div>
            <div class="form-group mb-3">
                <label for="password" class="form-label">Mot de passe</label>
                <input type="password" name='password' required maxlength="255"  minlength="4" class="form-control" id="password" placeholder="Password">
            </div>
            <button type="submit" class="btn btn-primary w-100">Se connecter</button>
        </form>
    </div>
</body>
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
</html>
