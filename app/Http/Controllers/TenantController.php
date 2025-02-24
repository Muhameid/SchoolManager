<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Http\Requests\TenantAjoutRequest;
use App\Repositories\TenantRepositoryInterface;

class TenantController extends Controller
{
    private $tenant_interface=null;
    public function __construct(){
        $this->tenant_interface=App::make(TenantRepositoryInterface::class);
    }   
    
    public function listeEcoles(){
        $interface=$this->tenant_interface;
        $result=$interface->listeEcoles();
        return view('ecoles.ecole', ['result'=>$result]);
    }

    public function ajouterTenant(){
        return view('ecoles.ajoutEcole');
    }

    public function enregistrement(TenantAjoutRequest $request) {
        $interface = $this->tenant_interface;
        $result = $interface->enregistrement($request);
        if (is_object($result)) {
            return redirect()->route('tenants_liste')->with('success', 'Ajout du tenant réussi !');
        } else {
            return redirect()->route('login')->with('error', 'Ajout du tenant échoué !');
        }
    }


    public function suppression($tenant_id)
    {
        $interface=$this->tenant_interface;
        $result=$interface->suppression($tenant_id);

        if (is_object($result)) {
            $message='suppression du tenant '.$result->nom.'('.$result->email.' ) réussi ';
            return redirect()->route('tenants_liste')->with('success',$message);
        } else {
            $message='suppression du tenant '.$result->nom.'('.$result->email.' ) échoué ';
            return redirect()->route('tenants_liste')->with('error', $message);
        }
    }

    

}
