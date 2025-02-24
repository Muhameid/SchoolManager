<?php

namespace App\Http\Controllers;

use App\Models\Filiere;
use App\Models\Matiere;
use App\Models\Professeur;
use Illuminate\Http\Request;
use App\Http\Requests\AjoutRequest;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AssocierMatiereRequest;
use App\Http\Requests\SupprimerMatiereRequest;
use App\Repositories\AdminRepositoryInterface;
use App\Repositories\FiliereRepositoryInterface;
use App\Http\Requests\FiliereAjoutRequest; // Ajout de l'import de FiliereAjoutRequest

class FiliereController extends Controller
{
    private $admin_interface=null;
    public function __construct(){
        $this->admin_interface=App::make(FiliereRepositoryInterface::class);
    }   

    public function index() {
        return view('dashboard.dashboardAdmin');
    }
    
    public function listeProfesseurs(){
        $interface=$this->admin_interface;
        $result=$interface->listeProfesseurs();
        return view('admin.listeProf', ['result'=>$result]);
        // $professeurs = User::where('usereable_type', 'Professeur')->get(['nom', 'prenom']);
        // return view('admin.listeProf', ['result'=>$result]);
    }


    public function ajoutProfesseur(){
        return view('admin.ajoutProf');
    }

    // public function enregistrementProfesseur(AjoutRequest $request) {
    //     $interface = $this->admin_interface;
    //     $result = $interface->enregistrementProfesseur($request);
    //     if (is_object($result)) {
    //         return redirect()->route('AjoutProfAdmin')->with('success', 'Ajout du tenant réussi !');
    //     } else {
    //         return redirect()->route('login')->with('error', 'Ajout du tenant échoué !');
    //     }
    // }

    public function listeFiliere(){
        $interface=$this->admin_interface;
        $result=$interface->listeFiliere();
        return view('admin.listeFiliere', ['result'=>$result]);
    }

    public function ajouterFiliere(Request $request){
        return view('admin.ajoutFiliere');
    }

    public function enregistrementFiliere(FiliereAjoutRequest $request) {

        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour ajouter une filière.');
        }
        
        $interface = $this->admin_interface;
        $result = $interface->enregistrementFiliere($request);
        
        if (is_object($result)) {
            return redirect()->route('ListeFiliereAdmin')->with('success', 'Ajout de la filière réussi !');
        } else {
            return redirect()->route('login')->with('error', 'Ajout de la filière échoué !');
        }
    }


    public function suppressionFiliere($filiere_id){
        $result = $this->admin_interface->suppressionFiliere($filiere_id);
    
        if ($result === false) {
            return redirect()->route('ListeFiliereAdmin')->with('error', 'Impossible de supprimer la filière, elle a des classes associées.');
        } elseif ($result === null) {
            return redirect()->route('ListeFiliereAdmin')->with('error', 'Filière non trouvée.');
        }
    
        return redirect()->route('ListeFiliereAdmin')->with('success', 'Filière supprimée avec succès.');
    }
    
    public function VueFiliere(int $filiere_id)
    {
        $filiere = Filiere::with(['matieres' => function($query){ $query->withPivot('professeur_id');}])->find($filiere_id);
        if ($filiere) {
            $matieresDisponibles = $this->admin_interface->listeMatieres($filiere_id);
            $matiereProfs = $this->admin_interface->matiereProfesseur();
            // dd( $matiereProfs );
           // dd($matieresDisponibles);
            return view('admin.filiere', ['filiere_id' => $filiere_id], compact('filiere', 'matieresDisponibles', 'matiereProfs'));
        } else {
            return redirect()->back()->with('error', 'Filière non trouvée');
        }
    }
        
    
    public function associerMatiere(AssocierMatiereRequest $request, int $id)
    {
    
        // Liste des matières sélectionnées
        $interface=$this->admin_interface;
        $result=$interface->associeMatiere($id, $request);
        if($result!==false)return redirect()->back()->with('success', 'Action bien réalisé');
        abort('404');
    }

    public function afficherClasseFiliere(){
        $interface=$this->admin_interface;
        $result=$interface->classeFiliere($id);

        return view('admin.filieresClassse', compact('filiere'));

    }
    
    
    
    public function supprimerMatiere(Request $request, $id)
    {
        $filiere = Filiere::findOrFail($id);
        $matiereIds = array_keys($request->input('matieres', []));
    
       
        foreach ($filiere->classes as $classe) {
            $classe->matieres()->detach($matiereIds);
        }
    
        
        $filiere->matieres()->detach($matiereIds);
    
        return redirect()->back()->with('success', 'Matières supprimées avec succès.');
    }
    
}
