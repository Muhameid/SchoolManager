<?php

namespace App\Repositories;
use Exception;
use App\Models\Pays;
use App\Models\User;
use App\Models\Eleve;
use App\Models\Ville;
use App\Models\Classe;
use App\Models\Domain;
use App\Models\Tenant;
use App\Jobs\SeederJob;
use App\Models\Filiere;
use App\Models\Matiere;
use App\Models\Professeur;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AjoutProfRequest;
use App\Http\Requests\AjoutEleveRequest;
use App\Http\Requests\AjoutMatiereRequest;
use App\Http\Requests\AssociationProfMatiereRequest;

class AdminRepository implements AdminRepositoryInterface {

    public function listeEleves(){
        $eleves = Eleve::with('classe.filiere')->orderby('id')->get();

        return $eleves;
    }
    
    public function attribuerClasse($id, $classeId){
        $eleve = Eleve::findOrFail($id);
        $classe = Classe::findOrFail($classeId);

        $eleve->classe()->associate($classe);
        $eleve->save();

        return true;
    }
   
    public function suppressionEleve($eleve_id) {
        $eleve = Eleve::with('classe_options')->find($eleve_id);
        if (is_object($eleve)) {
            if ($eleve->examens()->count() > 0) {
                return false;
            }
            
            else{

                try {
                  
 
                $eleve->delete();
                $eleve->user->delete();
                return true;
            }

                catch(Exception $e){
                    $message = $e->getMessage();
                    
                    $message = substr($message, strpos($message, "Retirer"));
                    $message = strstr($message, 'npm', true);   
                    return $message;
                }            
        }
        return false;
    }
    }

    
    private function ajouteleve(string $ine,string $login, string $nom, string $prenom,string  $adresse, $date_naissance,string  $telephone_1,int  $ville_id, string $password)
    {


  
        $eleve = new Eleve();
        $eleve->ine = $ine;
        $eleve->ville_id = $ville_id;
        
        if($eleve->save()){
            $user = new User();
            $user->login = $login;
            $user->nom = $nom;
            $user->prenom = $prenom;
            $user->usereable_id = $eleve->id;
            $user->usereable_type = Eleve::class;
            $user->adresse = $adresse;
            $user->date_naissance = $date_naissance;
            $user->telephone_1 = $telephone_1;
            $user->telephone_2 = '';
            $user->telephone_3 = '';
            $user->ville_id = $ville_id;
            $user->password = $password;  // Le mot de passe est déjà hashé
            if($user->save()) return $eleve;
            $eleve->delete();
        }
        // 🔹 Création de l'utilisateur
        return false;
        
        
    }

    public function enregistrementEleve(AjoutEleveRequest $request) {
        $id = $request->input('id');
        $login = $request->input('login');
        $ine = $request->input('ine');
        $nom = $request->input('nom');
        $prenom = $request->input('prenom');
        $adresse = $request->input('adresse');
        $date_naissance = $request->input('date_naissance');
        $telephone_1 = $request->input('telephone_1');
        $ville_id = $request->input('ville_id');
        $password = bcrypt($request->input('password'));

        $result = $this->ajouteleve( $ine,$login, $nom, $prenom, $adresse, $date_naissance, $telephone_1, $ville_id, $password);

        return response()->json(['success' => true, 'eleve' => $result]);
    }

    public function updateEleveTraitement(){

        $item= Eleve::find($request->$id);
        $item->nom = $request->nom;
        $item->prenom = $request->prenom;
        $item->ine= $request->ine;
        $item->date_naissance = $request->date_naissance;
    }


        public function listeClasses(){
            $classes = Classe::with('filiere')->orderby('id')->get();
           

            return $classes;
        }
       
    
        public function supprimerClasse($classe_id){
            $classe =   Classe::find($classe_id);
    
            if (is_object($classe)) {
                // verification si unefilière a des classes associées
                if ($classe->eleve()->count() > 0) {
    
                    return redirect()->route('listeClasseAdmin')->with('error', 'Impossible de supprimer la classe, elle a des éléves associés.');
                }
    
                // Si la filière n'a pas de classes associées, on peut la supprimer
                $classe->delete();
    
                return redirect()->route('listeClasseAdmin')->with('success', 'Classe supprimée avec succès.');
            }
    
            return redirect()->route('listeClasseAdmin')->with('erreur', 'Classe non trouvée.');
            }
           /* public function enregistrementClasseFiliere($nom,$filiere){
                $filiere = Filiere::find( $filiere);
                $classe = Classe::find( $classe);
               
               return $filiere;
               return $classe;
                
            }*/
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
        public function retirerClasse(int $id){
            $eleve = Eleve::with('classe')->findOrFail($id);
            $classeId = $eleve->first()->classe->first()->id;
            $classe = Classe::findOrFail($classeId);
    
            $eleve->classe()->dissociate($classe);
            $eleve->save();
    
            return true;
        }
        public function ficheEleve(int $id){
    
            $eleves = Eleve::with('user')
                            ->with('classe_options.matiere')
                            ->with('classe.filiere.matieres',)
                            ->where('id','=',$id)->first();  
            if(is_object($eleves)){
                    $id = $eleves->user->ville_id;
                    $villes = Ville::orderBy('nom')->with('users')->find($id);
                    $id = $villes->pays_id;
                    $pays = Pays::orderBy('nom')->find($id);
                //dd($id);
                return ['eleves' => $eleves,'villes' => $villes,'pays' => $pays];
            }
    
            return false;
            
        }
       
}