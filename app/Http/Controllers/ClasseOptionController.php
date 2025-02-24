<?php
namespace App\Http\Controllers;

use App\Models\Matiere;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\ClasseController;
use App\Http\Requests\ClasseOptionAjoutRequest;
use App\Repositories\ClasseOptionRepositoryInterface;
use App\Http\Requests\AssignerProfesseurOptionRequest;

class ClasseOptionController extends Controller
{
    private $classeOption_interface=null;
    public function __construct(){
        $this->classeOption_interface=App::make(ClasseOptionRepositoryInterface::class);
    }   

    public function listeClasseOption(){
    
        $interface=$this->classeOption_interface;
        $result=$interface->listeClasseOption();
      
        return view('admin.listeClasseOptionAdmin', ['result'=>$result]);
    
        
    }
   
 
    public function ajoutClasseOption(Request $request){
        $interface=$this->classeOption_interface;
        $matiere=$interface->ajoutClasseOption();
        return view('admin.ajoutClasseOptionAdmin',['result'=>$matiere]);
    }
    
public function enregistrementClasseOption(ClasseOptionAjoutRequest $request)
{
    $nom_classe = $request->input('nom'); 
    $matiere_id = $request->input('matiere_id');        

    $result = $this->classeOption_interface->enregistrementClasseOption($nom_classe,$matiere_id);

    
    if ($result) {
        return redirect()->route('listeClasseOptionAdmin')->with('success', 'Ajout de la classe option réussi !');
    } else {
        return redirect()->route('ajoutClasseOptionAdmin')->with('erreur', 'Ajout de la classe option échoué !');
    }
}
    
    public function infoclasseOption(int $id)
         {
            $interface = $this->classeOption_interface;
             $classeoption = $interface->infoclasseOption($id);
            if($classeoption)

            return view('admin.infoClasseOptionAdmin')->with(['classeoption'=>$classeoption]);
            if (!($classeoption)) {
                return view('admin.infoClasseOptionAdmin')->with('error', 'Erreur lors de l\'assignation du professeur.');
            }
        
         }
    
         public function supprimerClasseOption($option_id){
            $result = $this->classeOption_interface->supprimerClasseOption($option_id);
        
            if ($result === false) {
                return redirect()->route('listeClasseOptionAdmin')->with('error', 'Impossible de supprimer la classe option, elle a des classes associées.');
            } elseif ($result === null) {
                return redirect()->route('listeClasseOptionAdmin')->with('error', 'la classe option n\'existe pas');
            }
        
            return redirect()->route('listeClasseOptionAdmin')->with('success', 'la classe option à été supprimée avec succès.');
        }

        public function attribuerClasseOption(Request $request, $id)
        {
            $eleveId = $request->input('eleve_id');
        
            $result = $this->classeOption_interface->attribuerClasseOption($id, $eleveId);
            if ($result) {
                return redirect()->route('listeEleveAdmin')->with('success', 'Classe Option attribuée avec succès.');
            } else {
                return redirect()->route('listeEleveAdmin')->with('error', 'Erreur lors de l\'attribution de la classe Option.');
            }
        }

        public function retirerClasseOption($id)
        {
            $result = $this->classeOption_interface->retirerClasseOption($id);
            if ($result) {
                return redirect()->route('listeEleveAdmin')->with('success', 'Classe Option retirée avec succès.');
            } else {
                return redirect()->route('listeEleveAdmin')->with('error', 'Erreur lors de la retraite de la classe Option.');
            }
        }
        public function assignerProfesseurOption(AssignerProfesseurOptionRequest $request)
     {
         $interface = $this->classeOption_interface;
         $result = $interface->assignerProfesseurOptionTraitement($request);
         if ($result) {
            return redirect()->route('listeClasseOptionAdmin')->with('success', 'Modification du prof reussi - Classe option '.$request->input('classe_option_id'));
         } else {
            return redirect()->route('infoClasseAdmin',['id'=>$request->input('id_classe')])->with('erreur', 'Modification du prof échoué  - Classe option '.$request->input('classe_option_id'));
        } 
     }

}


?>