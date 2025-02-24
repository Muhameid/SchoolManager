<?php

namespace App\Http\Controllers;

use App\Models\Pays;
use App\Models\Eleve;
use App\Models\Ville;
use App\Models\Classe;
use App\Models\Filiere;
use App\Models\Matiere;
use App\Models\Professeur;
use App\Models\ClasseOption;
use Illuminate\Http\Request;
use App\Http\Requests\AjoutRequest;
use Illuminate\Support\Facades\App;
use App\Http\Requests\AjoutProfRequest;
use App\Http\Requests\AjoutEleveRequest;
use App\Http\Requests\ClasseAjoutRequest;
use App\Http\Requests\AjoutMatiereRequest;
use App\Repositories\AdminRepositoryInterface;
use NunoMaduro\Collision\Adapters\Phpunit\State;
use App\Http\Requests\AssociationProfMatiereRequest;

class AdminController extends Controller
{
    private $admin_interface=null;
    public function __construct(){
        $this->admin_interface=App::make(AdminRepositoryInterface::class);
    }   
    
    public function listeClasses(){
    
            $interface=$this->admin_interface;
            $result=$interface->listeClasses();
            return view('admin.listeClasseAdmin', ['result'=>$result]);
            

    }
    public function listeEleves(){

        $interface=$this->admin_interface;
        $result=$interface->listeEleves();
        $classes = Classe::All();
        $classesO = ClasseOption::All();
        return view('admin.listeEleveAdmin', ['result'=>$result , 'classes'=>$classes , 'classe_options'=>$classesO]);


}
    public function attribuerClasse(Request $request, $id)
{
    $classeId = $request->input('classe_id');

    $result = $this->admin_interface->attribuerClasse($id, $classeId);

    if ($result) {
        return redirect()->route('listeEleveAdmin')->with('success', 'Classe attribuée avec succès.');
    } else {
        return redirect()->route('listeEleveAdmin')->with('error', 'Erreur lors de l\'attribution de la classe.');
    }
}


public function ajoutEleve(){
    
    
    $pays = Pays::all(); 
    $villes = [];

    
    if (request()->has('pays_id')) {
        $villes = Ville::where('pays_id', request('pays_id'))->get();
        return $villes;
    }
    
    
    
     // Récupérer la liste des pays
    $interface = $this->admin_interface;
    $result = $interface->listeEleves();  // Récupérer la liste des élèves

    // Retourner la vue avec les deux variables
    return view('admin.ajoutEleveAdmin', [
        'result' => $result,
        'pays' => $pays,
        'villes' => $villes,
          
    ]);
}

public function suppressionEleve($eleve_id){
    $result = $this->admin_interface->suppressionEleve($eleve_id);
    if ($result == false) {
        return redirect()->route('listeEleveAdmin')->with('error', 'Impossible de supprimer l\'èleve, il faudrait supprimer ses examens.');
    } elseif ($result == null) {
        return redirect()->route('listeEleveAdmin')->with('error', 'Eleve non trouvée.');
    }elseif (is_string($result)) {
        return redirect()->route('listeEleveAdmin')->with('error', $result);
    }

    return redirect()->route('listeEleveAdmin')->with('success', 'Eleve supprimée avec succès.');
}




    public function enregistrementEleve(AjoutEleveRequest $request) {
        $interface = $this->admin_interface;
        $result = $interface->enregistrementEleve($request);
        if (is_object($result)) {
            return redirect()->route('listeEleveAdmin')->with('success', 'Ajout éléve réussi !');
        } else {
            return redirect()->route('listeEleveAdmin')->with('error', 'Ajout éléve échoué !');
        }
    }
  
    


    public function index()

    {

        $data['pays'] = Pays::get(["nom", "id"]);

        return view('admin.ajoutEleveAdmin', $data);

    }

    /**

     * Write code on Method

     *

     * @return response()

     */

    public function fetchState(Request $request)

    {

        $data['pays'] = State::where("pays_id", $request->pays_id)

                                ->get(["nom", "id"]);

  

        return response()->json($data);

    }

    /**

     * Write code on Method

     *

     * @return response()

     */

     public function fetchCity(Request $request)
     {
         $data['villes'] = Ville::where("pays_id", $request->pays_id)->orderby('nom', 'asc') // Assure-toi d'avoir un champ 'state_id' dans la table 'villes'
                                ->get(["nom", "id"]);
     
         return response()->json($data);
     }
     public function retirerClasse(int $id){
        $result = $this->admin_interface->retirerClasse($id);
        if ($result) {
            return redirect()->route('listeEleveAdmin')->with('success', 'Eléve retirée avec succès.');
        } else {
            return redirect()->route('infoClasseAdmin')->with('error', 'Erreur lors de la retraite de l\'éléve.');
        }
       }
     
       public function ficheEleve(int $id){
        $interface = $this->admin_interface;
        $result=$interface->ficheEleve($id);
            return view('admin.ficheEleve', $result);
        }
    }