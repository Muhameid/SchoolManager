<?php

namespace App\Repositories;

use App\Models\Filiere;
use App\Models\Matiere;
use App\Models\Professeur;
use Illuminate\Http\Request;
use App\Http\Requests\AjoutRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\FiliereAjoutRequest;
use App\Http\Requests\AssocierMatiereRequest;
use App\Repositories\FiliereRepositoryInterface;

class FiliereRepository implements FiliereRepositoryInterface {

    public function listeFiliere()
    {
        return Filiere::orderBy('nom', 'asc')->get();
    }

    public function listeMatieres(int $idFiliere = null){

        $matieres=Matiere::orderby('nom', 'asc')->with('filieres', function ($query) use ($idFiliere) {
            $query->withPivot('coefficient', 'professeur_id');
            if (is_numeric($idFiliere)) {
                $query->where('id', $idFiliere);
            }
        })->get();




        return $matieres;
        
    }
    public function matiereProfesseur(int $matiere_id=null, int $professeur_id=null){
        /*
        $professeurs=User::orderBy('nom', 'prenom');
        $professeurs->withWhereHas('professeurs', function($query)use ($professeur_id,$matiere_id ){
            if(!is_null($professeur_id))$query->where('id', '=',$professeur_id );
            $query->withWhereHas('matieres', function ($query) use ($matiere_id){
                $query->withPivot('matiere_id');
                if(!is_null($matiere_id))$query->wherePivot('matiere_id', '=', $matiere_id);
            });
           
        });*/
       
        $matieres=Matiere::orderby('nom', 'asc')
            ->withWhereHas('professeurs', function ($query) use ($professeur_id) {
                if (is_numeric($professeur_id)) {
                    $query->where('id', $professeur_id);
                }
                $query->with('user');
            }
        );
        if(!is_null($matiere_id))$matieres->where('id', $matiere_id);
        $matieres=$matieres->get();
    
            
         //dd($matieres);
        return $matieres;

    }


    public function listeProfesseurs(){
        return Professeur::with('user')->orderby('identifiant', 'desc')->get();
    }

    private function ajoutProfesseur($id, $nom){
        $professeur = new Professeur();
        $professeur->id = $id;
        $professeur->identifiant = $nom;
        // dd($professeur);
    }

    private function associeMatiereTraitement(int $filiere_id, array $idsMatiere){
        // dd($idsMatiere);
        $filiere = Filiere::find($filiere_id);

        if (is_object($filiere)) {
            if(count($idsMatiere))$filiere->matieres()->sync($idsMatiere);
            return $filiere->matieres()->count();
        }
        return false;
    }

    public function associeMatiere(int $filiere_id, AssocierMatiereRequest $request){
        $table=array();
        if(is_array($request->input('matieres') )){
            foreach($request->input('matieres')  as $key => $value){
                $temp['coefficient']=$request->input('coefficient')[$key];
                $temp['professeur_id']=$request->input('professeur')[$key];
                $table[$key]=$temp;
            }
        }

        return $this->associeMatiereTraitement($filiere_id, $table);
    }

    public function classeFiliere($id)
    {
        return Filiere::with('classes')->get()->orderby('id');
    }

    // public function enregistrementProfesseur(AjoutRequest $request)
    // {
    //     dd($request);
    //     $id = strtolower(trim($request->input('id')));
    //     $nom = $request->input('nom');
    //     return $this->ajoutProfesseur($id, $nom);
    // }

    public function ajouterFiliere($nom, $niveau, $description)
    {
        $filiere = new Filiere();
        $filiere->nom = $nom;
        $filiere->niveau = $niveau;
        $filiere->description = $description;

        return $filiere->save() ? $filiere : false;
    }

    public function enregistrementFiliere(FiliereAjoutRequest $request){
        $nom = $request->input('nom');
        $niveau = $request->input('niveau');
        $description = $request->input('description');

        return $this->ajouterFiliere($nom, $niveau, $description);
        
    }

    public function suppressionFiliere($filiere_id) {
        $filiere = Filiere::find($filiere_id);
    
        if (is_object($filiere)) {
            if ($filiere->classes()->count() > 0) {
                return false;
            }
    
            $filiere->matieres()->detach();
            $filiere->delete();
    
            return true;
        }
        return null;
    }
    
}
