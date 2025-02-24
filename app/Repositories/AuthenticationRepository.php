<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Auth;

class AuthenticationRepository implements AuthenticationRepositoryInterface {
    public function authenticate(array $credentials){
        if (Auth::attempt($credentials)) return true;   
        else return false;
    }

    public function authenticateTenant(array $credentials){
        if (Auth::attempt($credentials)) return Auth::user();   
        else return false;
    }
}