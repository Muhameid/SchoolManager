<?php
namespace App\Http\Controllers;

use App\Models\Pays;
use App\Models\Eleve;
use App\Models\Ville;
use App\Models\Classe;
use App\Models\Filiere;
use App\Models\Professeur;
use Illuminate\Http\Request;
use App\Http\Requests\AjoutRequest;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use App\Http\Requests\AjoutEleveRequest;
use App\Http\Requests\ClasseAjoutRequest;
use App\Repositories\ClasseRepositoryInterface;
use App\Http\Requests\AssignerProfesseurRequest;
use App\Http\Requests\AssignerProfesseurDeleteRequest;

class ClasseController extends Controller
{
    private $classe_interface=null;
    public function __construct(){
        $this->classe_interface=App::make(ClasseRepositoryInterface::class);
    }   
    public function listeClasses(){
    
        $interface=$this->classe_interface;
        $result=$interface->listeClasses();
        return view('admin.listeClasseAdmin', ['result'=>$result]);
        
}
/*public function listeEleves(){
    
    $interface=$this->classe_interface;
    $result=$interface->listeEleves();
    return view('admin.listeEleveAdmin', ['result'=>$result]);
}*/
public function ajoutClasse(){
    $filiere = Filiere::all();  // Correction : Utilise '::' au lieu de ':'
    return view('admin.ajoutClasseAdmin', ['result' => $filiere]);
}
public function enregistrementClasse(ClasseAjoutRequest $request)
{
    $nom_classe = $request->input('nom');         // Nom de la classe
    $filiere_id = $request->input('filiere_id'); 

    $result = $this->classe_interface->enregistrementClasse($nom_classe, $filiere_id);

    // Vérification du résultat
    if ($result) {
        return redirect()->route('listeClasseAdmin')->with('success', 'Ajout de la classe réussi !');
    } else {
        return redirect()->route('ajoutClasseAdmin')->with('erreur', 'Ajout de la classe échoué !');
    }
}
    public function supprimerClasse($classe_id)
    {
        $interface = $this->classe_interface;
        $result = $interface->supprimerClasse($classe_id);
        if($result){
            $erreur = 'success';
            $texte = 'Classe supprimée avec succès.';
        }
        else{
            $erreur = 'error';
            $texte = 'Impossible de supprimer la classe,  elle a des élèves associés.';
        }
        return redirect()->route('listeClasseAdmin')->with($erreur, $texte);
    }
    public function infoclasse(int $id)
     {
        $interface = $this->classe_interface;

        
         $classe = $interface->infoclasse($id);
         

        if(is_object( $classe))return view('admin.infoClasseAdmin')->with(['classe'=>$classe]);
        abort('404');
        if ($classes) {
        }

        
        return redirect()->view('admin.infoClasseAdmin')->with('error', 'Erreur lors de l\'assignation du professeur.');
    
     }

     public function assignerProfesseurDelete(AssignerProfesseurDeleteRequest $request){
        $interface = $this->classe_interface;
        $result = $interface->assignerProfesseurDeleteTraitement($request);

        if ($result) {
            return redirect()->route('infoClasseAdmin',['id'=>$request->input('id_classe')])->with('success', 'Suppression du prof reussie !');
         } else {
            return redirect()->route('infoClasseAdmin',['id'=>$request->input('id_classe')])->with('success', 'Suppression du prof échouée !');
        } 
     }
     
     
     public function assignerProfesseur(AssignerProfesseurRequest $request)
     {
         $interface = $this->classe_interface;
         $result = $interface->assignerProfesseurTraitement($request);
 
        if ($result) {
            return redirect()->route('infoClasseAdmin',['id'=>$request->input('id_classe')])->with('success', 'Ajout du prof reussi !');
         } else {
            return redirect()->route('infoClasseAdmin',['id'=>$request->input('id_classe')])->with('success', 'Ajout du prof échoué !');
        } 
     }
}

