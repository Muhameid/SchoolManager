<?php

namespace App\Http\Controllers;

use App\Models\Pays;
use App\Models\Ville;
use App\Models\Classe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Http\Requests\AjoutProfRequest;
use App\Http\Requests\AjoutExamenRequest;
use App\Http\Requests\AjoutMatiereRequest;
use NunoMaduro\Collision\Adapters\Phpunit\State;
use App\Repositories\ProfesseurRepositoryInterface;
use App\Http\Requests\AssociationProfMatiereRequest;

class ProfesseurController extends Controller
{
    private $professeur_interface=null;
    public function __construct(){
        $this->professeur_interface=App::make(ProfesseurRepositoryInterface::class);
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
         $data['villes'] = Ville::where("pays_id", $request->pays_id) // Assure-toi d'avoir un champ 'state_id' dans la table 'villes'
                                ->get(["nom", "id"]);
     
         return response()->json($data);
     }
     
     public function listeProfesseurs(){
        $interface=$this->professeur_interface;
        $result=$interface->listeProfesseurs();
        return view('admin.listeProfAdmin', ['result'=>$result]);

    }


public function ajoutProfesseur(){
    
    
    $pays = Pays::all(); 
    $villes = [];

    
    if (request()->has('pays_id')) {
        $villes = Ville::where('pays_id', request('pays_id'))->get();
        return $villes;
    }
    
    $interface = $this->professeur_interface;
    $result = $interface->listeProfesseurs();
    
    // Retourner la vue avec les deux variables
    return view('admin.ajoutProf', [
        'result' => $result,
        'pays' => $pays,
        'villes' => $villes,
          
    ]);
}


public function enregistrementProfesseur(AjoutProfRequest $request) {
        $interface = $this->professeur_interface;
        $result = $interface->enregistrementProfesseur($request);

        if (is_object($result)) {
            return redirect()->route('listeProfAdmin')->with('success', "Ajout d'un professeur réussi !");
        } else {
            return redirect()->route('listeProfAdmin')->with('erreur', "L'ajout de professeur a échoué !");
        }

}

    public function supprimerProfesseur($professeur_id)
    {
        $interface = $this->professeur_interface;
        $result=$interface->supprimerProfesseur($professeur_id);
        if ($result) {
            $message='suppression d un professeur  réussi ';
            return redirect()->route('listeProfAdmin')->with('success',$message);
        } else {
            $message='suppression d une Professeur échoué(vérifier si le prof est associé à une matiere)';
            return redirect()->route('listeProfAdmin')->with('error', $message);
        }
    }



    public function listeMatiere(){
        $interface=$this->professeur_interface;
        $result=$interface->listeMatiere();
        return view('admin.listeMatiere', ['result'=>$result]);
    }
    
    public function ajoutMatiere(){
        return view('admin.ajoutMatiere');
    }

    public function enregistrementMatiere(AjoutMatiereRequest $request) {
        $interface = $this->professeur_interface;
        $result = $interface->enregistrementMatiere($request);

        if ((is_object($result))) {
            return redirect()->route('listeMatiereAdmin')->with('success', 'Ajout réussi !');
        } else {
            return redirect()->route('login')->with('error', 'Ajout  échoué !');
        }
    }

    public function supprimerMatiere($matiere_id)
    {
        $interface = $this->professeur_interface;
        $result=$interface->supprimerMatiere($matiere_id);
        if ($result) {
            $message='suppression d une matiere réussi ';
            return redirect()->route('listeMatiereAdmin')->with('success',$message);
        } else {
            $message='suppression d une matiere échoué (vérifier si la matiere est associé à un prof) ';
            return redirect()->route('listeMatiereAdmin')->with('error', $message);
        }
    }


    
    public function associationProfesseurMatiere(AssociationProfMatiereRequest $request){
        $interface = $this->professeur_interface;
        $result=$interface->associationProfesseurMatiere($request);
        if ($result!==false) {
            $message='Bravo le prof est desormais associé à '.$result.' matieres.';
            return redirect()->route('ficheProfesseur', ['id'=> $request->input('professeur_id')])->with('succes', $message);
        }
        $message='echec';
        return redirect()->route('ficheProfesseur', ['id'=> $request->input('professeur_id')])->with('error', $message);

    }
    
    public function ficheProfesseur($id){
        $interface = $this->professeur_interface;
        $result=$interface->ficheProfesseur($id);
            return view('admin.ficheProfesseur', $result);
        }

        

     


     

        public function associerExamen(Request $request, $id)
        {
            $interface = $this->professeur_interface;
        
            $result = $this->admin_interface->associerExamen($id, $classeId);
        
            if ($result) {
                return redirect()->route('listeClassesProf')->with('success', 'Examen attribuée avec succès.');
            } else {
                return redirect()->route('listeClassesProf')->with('error', 'Erreur lors de l\'attribution de l\'examen.');
            }
        }

        public function ficheMatiere(int $matiereId){
            $interface = $this->professeur_interface;
            $matiere = $interface->ficheMatiere($matiereId);
            $filieres = $interface->recupererFilieresParMatiere($matiereId);
            $professeurs = $interface->recupererProfesseursParMatiere($matiereId);
        
        return view('admin.ficheMatiere', [
            'matiere' => $matiere,
            'filieres' => $filieres,
            'professeurs' => $professeurs,
              
        ]);
    }
       
    }