<?php

namespace App\Repositories;
use App\Models\User;
use App\Models\Eleve;
use App\Models\Classe;
use App\Models\Filiere;
use App\Models\Matiere;
use App\Models\Professeur;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AjoutEleveRequest;
use App\Http\Requests\AssignerProfesseurRequest;
use App\Http\Requests\AssignerProfesseurDeleteRequest;

class ClasseRepository implements ClasseRepositoryInterface {



    public function listeClasses(){
        $classes = Classe::with('filiere.matieres')->with('matieres')->orderby('id')->get();


        return $classes;
    }
               
        public function enregistrementClasse($nom,$filiere){
            $filiere = Filiere::find( $filiere);
           
            if(is_object($filiere)){

                $classe=New Classe();
            
                $classe->nom=$nom;
                
    
                $classe->filiere_id=$filiere->id;
               
                    if($classe->save()){
                       return $classe;
                    }else return false;
            }
            
        }

    
      
        
        public function infoClasse(int $id_classe)
        {
            
            $classe=Classe::with(['filiere' => function( $query){
                $query->with(['matieres' => function ($query){
                    $query->withPivot('coefficient', 'filiere_id', 'matiere_id');
                    $query->withTimestamps();
                }]);
            }])
            ->with(['professeurs'=>function($query){
                $query->withPivot('matiere_id','classe_id');
                $query->with('user');
            }])
            ->with(['eleves' => function($query) {
                $query->with('user');
            }])
            ->where('id', '=', $id_classe)
            ->first();
            $prof_matieres=array();
            foreach ($classe->professeurs as $professeur) {
                $prof_matieres[$professeur->pivot->matiere_id] = $professeur;
            }
            
            if(is_object($classe->filiere)){
              
                foreach($classe->filiere->matieres as $matiere){
                    $matiere->liste_prof=collect();
                    
                    if(!isset($prof_matieres[$matiere->id])){
                        $idMatiere=$matiere->id;  
                        
                        $toto=Professeur::whereHas('matieres',
                            function($query)use($idMatiere){
                            
                                $query->where('id', '=', $idMatiere);
                            }
                        )->with('user')->get();
                       
                        $matiere->liste_prof=$toto;
                    }
                }
            }
 
           
              
           if(is_object($classe))return $classe;
           return false; 
            
        }


        public function assignerProfesseurDeleteTraitement(AssignerProfesseurDeleteRequest $request){
            $a=$request->input('id_classe');
            $b=$request->input('matiere_id');
           
            return $this->assignerProfesseurDeleteProfesseur($a,$b);      
        }
       
        public function assignerProfesseurTraitement( AssignerProfesseurRequest $assignerProfesseur)
        {
            $a=$assignerProfesseur->input('id_classe');
            $b=$assignerProfesseur->input('matiere_id');
            $c=$assignerProfesseur->input('professeur_id');
           
            return $this->assignerProfesseur($a,$b,$c);
                
     
        }

        public function supprimerClasse(int $id){
            $classe = Classe::find($id);
            if (!$classe) {
                return $classe->delete();
            }
            return false; 
        }
        private function assignerProfesseurDeleteProfesseur(int $id_classe, int $id_matiere)
        {
            $classe = Classe::find($id_classe);
            $matiere = Matiere::find($id_matiere);
        
            if (!$classe || !$matiere) {
                return false; 
            }
        
            $professeur = $classe->professeurs()->wherePivot('matiere_id', $matiere->id)->first();
        
            if (!$professeur) {
                return false; 
            }
        
            $classe->professeurs()->detach($professeur->id); 
        
            return true; 
        }

    
        private function assignerProfesseur(int $id_classe, int $id_matiere, int $id_professeur)
        {
            $classe = Classe::find($id_classe);
            $matiere = Matiere::find($id_matiere);
            $professeur = Professeur::find($id_professeur);

            
            if (!$classe || !$matiere || !$professeur) {
            
                return false;
            }

        
            $classe->professeurs()->attach($professeur->id, ['matiere_id' => $matiere->id]);

            
            return true;
        }



        
}
 