<?php

namespace App\Repositories;

use App\Models\Pays;
use App\Models\User;
use App\Models\Ville;
use App\Models\Examen;
use App\Models\Filiere;
use App\Models\Matiere;
use App\Models\Professeur;
use App\Http\Requests\AjoutProfRequest;
use App\Http\Requests\AjoutExamenRequest;
use App\Http\Requests\AjoutMatiereRequest;
use App\Repositories\ProfesseurRepositoryInterface;
use App\Http\Requests\AssociationProfMatiereRequest;



class ProfesseurRepository implements ProfesseurRepositoryInterface {


    public function listeProfesseurs()
    {
            $professeurs = Professeur::with('user')->orderby('identifiant', 'desc')->get();
            //dd($professeurs);
            return $professeurs;
        }
    
        public function ficheMatiere($matiereId){
        return Matiere::with('filieres.classes.eleves')->findOrFail($matiereId);
    }
        

    public function recupererFilieresParMatiere(int $matiereId) {
        return Filiere::whereHas('matieres', function ($query) use ($matiereId) {
                $query->where('matieres.id', $matiereId);
            })
            ->withCount('classes') 
            ->get()
            ->map(function ($filiere) {
                return [
                    'nom' => $filiere->nom,
                    'niveau' => $filiere->niveau,
                    'nombre_classes' => $filiere->classes_count
                ];
            });
    }
    
    public function recupererProfesseursParMatiere(int $matiereId) {
        return Professeur::whereHas('matieres', function ($query) use ($matiereId) {
                $query->where('matieres.id', $matiereId);
            })
            ->with('user')
            ->get()
            ->map(function ($professeur) {
                return [
                    'identifiant' => $professeur->identifiant,
                    'nom' => $professeur->user->nom ?? 'N/A',
                    'prenom' => $professeur->user->prenom ?? 'N/A',
                    'email' => $professeur->user->login ?? 'N/A',
                    'telephone' => $professeur->user->telephone_1 ?? 'N/A',
                ];
            });
    }
    

    public function listeMatiere(){
        $matieres = Matiere::orderby('id','desc')->get();
        return $matieres;
    }



    private function ajoutProfesseur(string $identifiant,string $login, string $nom, string $prenom,string  $adresse, $date_naissance,string  $telephone_1,int  $ville_id, string $password)
    {
    
    
      
        
        $professeur = new Professeur();
        $professeur->identifiant = $identifiant;  
         
    
        if($professeur->save()){
            $user = new User();
            $user->login = $login; 
            $user->nom = $nom;
            $user->prenom = $prenom;
            $user->usereable_id = $professeur->id;  
            $user->usereable_type = Professeur::class;  
            $user->adresse = $adresse;
            $user->date_naissance = $date_naissance;
            $user->telephone_1 = $telephone_1;
            $user->telephone_2 = $telephone_1;
            $user->telephone_3 = $telephone_1;
            $user->ville_id = $ville_id;
            $user->password = $password;  

        if($user->save()) return $professeur;
        $professeur->delete();

        }
        return false;
    }   
        
        public function enregistrementProfesseur(AjoutProfRequest $request){
            // dd($request);
            $identifiant = $request->input('identifiant');
            $login=$request->input('login');
            $nom=$request->input('nom');
            $prenom = $request->input('prenom');
            $adresse = $request->input('adresse');
            $date_naissance = $request->input('date_naissance');
            $ville_id = $request->input('ville_id');
            $telephone_1 = $request->input('telephone_1');
            $telephone_2 = $request->input('telephone_2', null); // Optionnel
            $telephone_3 = $request->input('telephone_3', null); // Optionnel
            $password = bcrypt($request->input('password'));
            $result=$this->ajoutProfesseur($identifiant,$login, $nom, $prenom,  $adresse, $date_naissance, $telephone_1, $ville_id, $password);
            return response()->json(['success' => true, 'professeur' => $result]);
            } 
           
    
    
    
            public function supprimerProfesseur($professeur_id)
            {
                $prof = Professeur::with('matieres')->find($professeur_id);
                if(is_object($prof) && (count($prof->matieres)==0)){
                $prof->user->delete();
                return $prof->delete();
                }
                return false;
            }
    


    
    

        private function ajoutMatiere(string $nom, string $description){
            if(!(Matiere::where('nom','=', $nom)->exists())){
                $matiere=new Matiere();
                $matiere->nom=$nom;
                $matiere->description=$description;
                if ($matiere->save()){
                return $matiere;
                }
            }
            return redirect()->back()->with('error', 'Veuillez detacher le prof de la ou les matieres');
        }

        public function enregistrementMatiere(AjoutMatiereRequest $request){
            $nom=$request->input('nom');
            $description=$request->input('description');
            $result=$this->ajoutMatiere($nom,$description);
            return $result;

        }
        
        public function supprimerMatiere($matiere_id)
        {
            $matiere = Matiere::find($matiere_id);
            if(is_object($matiere) && (count($matiere->professeurs)==0))
            return $matiere->delete();
            return false;
        
        }

        
    private function associationProfesseurMatiereTraitement(int $professeur_id, array $idsMatiere){
        // dd($idsMatiere);
        $professeur = Professeur::find($professeur_id);

        if (is_object($professeur)) {
            if(count($idsMatiere))$professeur->matieres()->sync($idsMatiere);
            return $professeur->matieres()->count();
        }
        return false;
    }

    public function associationProfesseurMatiere(AssociationProfMatiereRequest $request)
    {
        $professeur_id = $request['professeur_id'];
        $table=array();
        if(isset($request['matieres']) && is_array($request['matieres'])){
            foreach($request['matieres'] as $matiere){
                $table[]=$matiere;
            }
        }
  
        return $this->associationProfesseurMatiereTraitement($professeur_id,$table);
    }



   public function ficheProfesseur(int $id){
    
        $professeurs = Professeur::with('user')
                            ->with('matieres')
                            ->with('examens')
                            ->with('classeOptions.eleves')
                            ->with('classes', function($query){
                                $query->withPivot('classe_id', 'matiere_id', 'professeur_id');
                                $query->with('filiere');
                                $query->withTimestamps();
                            })
                            ->where('id','=',$id)->first();  
      
        if(is_object( $professeurs)){   
            $matieres = Matiere::orderBy('nom')->with('professeurs', function($query)use($id){
                $query->where('id', '=', $id);
            })->get();
            //dd($matieres);

                $id = $professeurs->user->ville_id;
                $villes = Ville::orderBy('nom')->with('users')->find($id);
                $id = $villes->pays_id;
                $pays = Pays::orderBy('nom')->find($id);
                
       
            return ['professeurs' => $professeurs,'matieres' => $matieres,'villes' => $villes,'pays' => $pays ];
        }

        return false;
        
    }
  

public function listeClassesProfesseur()
{
$profid= auth()->id();
  
    $professeur = Professeur::with(['classes' => function ($query) {
        $query->with('filiere');
        $query->with('eleves');
        $query->with('matieres');
        $query->withPivot('classe_id', 'matiere_id', 'professeur_id');
    }])
    ->find($profid);

    $infos= $professeur->classes; 
    return $infos;
} 

public function ajoutExamen()
{
    $profid= auth()->id();
  
    $professeur = Professeur::with('matieres')->find($profid);

    $infos= $professeur->matieres; 
    return $infos;
}




}