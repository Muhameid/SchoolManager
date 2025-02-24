<?php

namespace App\Repositories;

use App\Models\Eleve;
use App\Models\Matiere;
use App\Models\ClasseOption;
use App\Http\Requests\AssignerProfesseurOptionRequest;

class ClasseOptionRepository implements ClasseOptionRepositoryInterface {

    public function listeClasseOption()
        {
            $classes = ClasseOption::with(['matiere.professeurs.user', 'professeur.user'])->orderBy('nom')->get();
            $professeursHabilitesParMatiere = [];
        
            foreach ($classes as $classe) {
                
                if ($classe->matiere) {
                    $nomsProfesseurs = [];
        
                    foreach ($classe->matiere->professeurs as $professeur) {
                        $idProfesseur = $professeur->user->id;
                        $nomProfesseur = $professeur->user->nom;
                        $prenomProfesseur = $professeur->user->prenom;
        
                        $nomsProfesseurs[] = $nomProfesseur . ' ' . $prenomProfesseur . ' #' . $idProfesseur;
                    }
        
                    $professeursHabilitesParMatiere[$classe->matiere->id] = [
                        'matiere' => $classe->matiere->nom,
                        'professeurs' => $nomsProfesseurs
                    ];
                }
            }
        
            return [
                'classes' => $classes,
                'professeursHabilitesParMatiere' => $professeursHabilitesParMatiere
            ];
        }
 
    public function ajoutClasseOption(){
        $matiere = Matiere::orderby('nom')->get();
        return  $matiere;
    }

    public function enregistrementClasseOption($nom,$matiere_id){

        $matiere = Matiere::find( $matiere_id);

        if(is_object($matiere)){

            $classeoption=New ClasseOption();

            $classeoption->nom=$nom;
            $classeoption->matiere_id=$matiere->id;

                if($classeoption->save()){
                    return $classeoption;
                }else return false;
        }
        }

        public function supprimerClasseOption($option_id)
        {
            $classe = ClasseOption::find($option_id);
            if(is_object($classe)){
            $classeOption = $classe->delete();
            return $classeOption;
            }
            return false;
        }
    
    public function infoClasseOption(int $id)
    {
    $classeoption = ClasseOption::with('matiere')->with('eleves.user')
                    ->with('professeur')
                    ->where('id','=',$id)->first();
                    $eleves = Eleve::all();
                    if(is_object($classeoption)){   
                        return ['classe_option'=>$classeoption,'eleves'=>$eleves];
                    }
                    
                return false;               
    }
            
    public function attribuerClasseOption(int $classeId, int $eleveId)
    {
        $eleve = Eleve::findOrFail($eleveId);
        $classe_options = ClasseOption::find($classeId);
    
        $matieresClasseOption = $classe_options->matiere()->select('id')->first();
        $idMatiere = $matieresClasseOption->id;
    
    
        $matieresFiliereExist = Matiere::whereHas('filieres.classes.eleves', function($query)use ($eleveId) {
            $query->where('id', '=', $eleveId);
        })->where('id', '=', $idMatiere)->exists();
       // dd( $matieresFiliereExist);
    
    
        if (!$matieresFiliereExist) {
            $eleve->classe_options()->attach($classeId);
            $eleve->save();
            return true;
        }
    
        return false;
    }

    public function retirerClasseOption($id){
        $eleve = Eleve::findOrFail($id);
        $eleve->classe_options()->detach();
        $eleve->save();

        return true;
    }


public function assignerProfesseurOptionTraitement(AssignerProfesseurOptionRequest $request)
    {
        $id_classe_option = $request->input('classe_option_id');
        $id_professeur = $request->input('professeur_id');


        return $this->assignerProfesseurOption($id_classe_option,$id_professeur);
    }

    private function assignerProfesseurOption(int $id_classe_option, int $id_professeur=null)
    {
        $classeOption = ClasseOption::find($id_classe_option);
        if(is_object($classeOption)){
            $classeOption->professeur_id=$id_professeur;
            return $classeOption->save();
        }
        return false ; 

    }
       

}