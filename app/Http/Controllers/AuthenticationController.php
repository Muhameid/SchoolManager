<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AuthenticationRequest;
use App\Repositories\AuthenticationRepositoryInterface;

class AuthenticationController extends Controller
{   

    private $authentication_interface = null;

    public function __construct(){

        $this->authentication_interface = App::make(AuthenticationRepositoryInterface::class);
        
    }

    public function show(){
        if(is_null(tenant()))$central=true; 
        else $central=false;
        return view('login.login', ['central'=>$central]);
    }

    public function authenticate(AuthenticationRequest $request) {

        $credentials = $request->only('login', 'password');
        // dd(Auth::attempt($credentials));

        $interface = $this->authentication_interface; 
        $result = $interface->authenticate($credentials);
    if ($result) {
        $request->session()->regenerate();

        return redirect()->intended('dashboard');
    }

    return back()->withErrors([
        'login' => 'Identifiant ou mot de passe incorrect',
    ])->onlyInput('login');

    }

    public function dashboard(){
        return view('dashboard.dashboard');
    }


    public function logout(){
        Auth::logout();
        return redirect()->route('login');

    }

    public function authenticateTenant(AuthenticationRequest $request) {

        $credentials = $request->only('login', 'password');
        // dd(Auth::attempt($credentials));

        $interface = $this->authentication_interface; 
        $result = $interface->authenticateTenant($credentials);
    if (is_object($result)) {
        $request->session()->regenerate();

        if ($result->usereable_type == 'App\Models\Administrateur') {
            return redirect()->route('dashboard_admin');
        }

        if ($result->usereable_type == 'App\Models\Professeur') {
            return redirect()->route('dashboard_professeur');
        }

        if ($result->usereable_type == 'App\Models\Eleve') {
            return redirect()->route('dashboard_eleve');
        }

    }
    return back()->withErrors([
        'login' => 'Identifiant ou mot de passe incorrect',
    ])->onlyInput('login');


   } 
   public function dashboardAdmin(){
        return view('dashboard.dashboardAdmin');
    }
    public function dashboardProfesseurs(){
        return view('dashboard.dashboardProfesseur');
    }
    public function dashboardEleve(){
        return view('dashboard.dashboardEleve');
    }
}