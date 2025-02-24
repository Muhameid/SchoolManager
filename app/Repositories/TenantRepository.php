<?php

namespace App\Repositories;

use DB;
use App\Models\Domain;
use App\Models\Tenant;
use App\Jobs\SeederJob;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;
use App\Http\Requests\TenantAjoutRequest;

class TenantRepository implements TenantRepositoryInterface {

    public function listeEcoles(){
        $tenants = Tenant::orderBy('id')->with('domains')->get();

       return $tenants;
        
    }

    public function enregistrement(TenantAjoutRequest $request){
        // dd($request);
        $id=strtolower(trim($request->input('id')));
        $nom=$request->input('nom');
        $email=trim($request->input('email'));
        $bdd_name='tenant'.$id;
        $domaine=$id.'.'.env('APP_URL');
        $domaine_valeur=strtolower($domaine);
        $result=$this->ajoutTenant($id,$nom,$email,$bdd_name,$domaine_valeur);
        return $result;
    }

    private function ajoutTenant($id,$nom,$email,$bdd_name,$domaine_valeur){
        $tenant=New Tenant();
        $tenant->id=$id;
        $tenant->nom=$nom;
        $tenant->email=$email;
        $tenant->tenancy_db_name=$bdd_name;
        // dd($tenant);

        if($tenant->save()){
            $domaine=New Domain();
            $domaine->domain=$domaine_valeur;
            $domaine->tenant_id=$tenant->id;
            if($domaine->save()){
                $job=new SeederJob($tenant->id);
                $job->onConnection('database');
                $job->dispatch($tenant->id);
                return $tenant;
            }else return false;

        }

    }


    public function suppression($tenant_id)
    {
        $tenant = Tenant::find($tenant_id);
        $tenant_retour=$tenant;
        if (is_object($tenant)){
            Domain::where('tenant_id', $tenant->id)->delete();
            $bdd = $tenant->tenancy_db_name;
            DB::statement("
                SELECT pg_terminate_backend(pid)
                FROM pg_stat_activity
                WHERE datname = '".$bdd."';
            ");
            $tenant->delete();
            return $tenant_retour;
        }
        return false;


    // Supprimer la base de données du tenant

    // Supprimer le tenant lui-même

    return true;
}


}