<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AuthenticationRequest;

class TestController extends Controller
{
    public function test(){
        return view('login.login');
    }

    public function test2(){
        return view('login.register');
    }

    public function authenticate(AuthenticationRequest $request) {
        //dd($request);
    }

    public function listeProfesseurs(){
        return view('ListeProf');

    }

    public function mesClasses(){
        return view('MesClasses');

    }
}
