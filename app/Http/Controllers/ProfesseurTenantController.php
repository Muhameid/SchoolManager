<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\Examen;
use Illuminate\Http\Request;
use App\Http\Requests\NoteRequest;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\AjoutExamenRequest;
use App\Http\Requests\AssocierExamenRequest;
use App\Http\Requests\RetirerEleveExamenRequest;
use App\Repositories\ProfesseurTenantRepositoryInterface;

class ProfesseurTenantController extends Controller
{
    private $professeur_tenant_interface=null;
    public function __construct(){
        $this->professeur_tenant_interface=App::make(ProfesseurTenantRepositoryInterface::class);
    } 

    public function listeClassesProf(){
        $interface=$this->professeur_tenant_interface;
        $a=$interface->listeClassesProf(Auth::user()->id);
        $b=$interface->listeOptionsProf(Auth::user()->id);
        $c=$interface->listeResponsablesProf(Auth::user()->id);

        $result['listeClassesProf']=$a;
        $result['listeOptionsProf']=$b;
        $result['listeResponsablesProf']=$c;
       

//dd($result);
        return view('professeurs.listeClassesProf', ['result'=>$result]);
    }

    
    public function listeClassesOptionsProf(){
        $interface=$this->professeur_tenant_interface;
        $option=$interface->listeClassesProf(Auth::user()->id);
//dd($result);
        return view('professeurs.listeClassesProf', ['option'=>$option]);
    }
    public function infoEleveProfesseur(){
        $interface=$this->professeur_tenant_interface;
        $infoep=$interface->infoEleveProfesseur(Auth::user()->id);
//dd($result);
        return view('professeurs.ficheExamen', ['infoep'=>$infoep]);
    }
    public function listeElevesProf(){
        $interface=$this->professeur_tenant_interface;
        $result=$interface->listeElevesParClasse(Auth::user()->id);
        $eleves = $result['eleves'];
        return view('professeurs.listeElevesProf',['eleves'=>$eleves]);

    }

        public function mesMatieres()
    {   $interface = $this->professeur_tenant_interface;
        $examens = $interface->mesMatieres(Auth::user()->id);
        
        return view('professeurs.creationExamenProf', ['examens' => $examens]);
    }
    public function getFilieres($matiereId)
    {
        // Récupérer les filières associées à la matière
        $filieres = Filiere::whereHas('matieres', function ($query) use ($matiereId) {
            $query->where('matieres.id', $matiereId);
        })->get();

        return response()->json($filieres);
    }
    

   

    public function enregistrementExamen(AjoutExamenRequest $request) {
       
    $interface = $this->professeur_tenant_interface;
    $result = $interface->enregistrementExamen($request);
   
    if (is_object($result)) {
        return redirect()->route('creationExamen')->with('success', 'Ajout examen réussi !');
    } else {
        return redirect()->route('creationExamen')->with('error', 'Ajout examen échoué !');
    }
}




public function associerExamen(AssocierExamenRequest $request) {
    $interface=$this->professeur_tenant_interface;
    $result=$interface->associerExamen($request);
    if ($result){
        return redirect()->route('associer_examen')->with('success', 'Examen attribuée avec succès.');
    } else {
        return redirect()->route('associer_examen')->with('error', 'Erreur lors de l\'attribution de l\'examen.');
    }
}
public function retirerExamenEleve(RetirerEleveExamenRequest $request)
{
    $examen_id = $request->input('examen_id'); // Récupérer l'ID de l'examen
    $interface = $this->professeur_tenant_interface;

    $result = $interface->retirerExamenEleve($request);
    if ($result) {
        return redirect()->route('FicheExamen', ['id' => $examen_id])->with('success', 'Élèves retirés avec succès.');
    } else {
        return redirect()->route('FicheExamen', ['id' => $examen_id])->with('error', 'Erreur lors du retrait des élèves.');
    }
}

public function listeExamens(){
    $interface = $this->professeur_tenant_interface;
    $result=$interface->listeExamens();

    return view('professeurs.listeExamens', ['result' => $result]);
}

public function ficheExamen($id){
    $interface = $this->professeur_tenant_interface;
    $result = $interface->FicheExamen($id);
$professeur = $result['professeur'];
$professeur->classes = $professeur->classes->unique('id'); 

return view('professeurs.ficheExamen', [
    'examen' => $result['examen'], 
    'professeur' => $professeur
]);
    }

    public function listeClassesExamensProf(){
        $interface=$this->professeur_tenant_interface;
        $result=$interface->listeClassesProf(Auth::user()->id);
  
        return view('professeurs.ficheExamen', ['result'=>$result]);
    }

    
    public function PageNote(int $id){
        $interface = $this->professeur_tenant_interface;
        $result=$interface->PageNote($id);
        return view('professeurs.note',['examen' => Examen::find($id)]);
    }



    public function Note(int $id, NoteRequest $request)
    {
        // Appelle la méthode du repository pour mettre à jour les notes
        $result = $this->professeur_tenant_interface->Note($id, $request);
    
        if (!$result) {
            // Si la mise à jour réussit, retourne la vue avec les résultats
            return redirect()->route('FicheExamen', ['id' => Examen::find($id)])->with('success', 'la notation à  été éffectué avec succés.');
        } else {
            // Sinon, redirige avec un message d'erreur
            return redirect()->route('FicheExamen', ['id' => Examen::find($id)])->with('error', 'Erreur lors de la notation.');
        }
    }

   

    public function download($filename)
    {

        $filePath = Storage::disk('local')->path('examens/'.$filename);
        if (file_exists($filePath)) {
            return response()->download($filePath);
        }
        return redirect()->back()->with('error', 'Le fichier n\'existe pas.');
    }

    public function supprimerExamen(int $id){
        $interface = $this->professeur_tenant_interface;
        $result = $interface->supprimerExamen($id);
        if ($result) {
            $message='suppression d\'un examen réussi ';
            return redirect()->route('liste_examens')->with('success',$message);
        } else {
            $message='suppression d\'un examen  échoué , il y\'a déjà des éléves programmés pour cet examen';
            return redirect()->route('liste_examens')->with('error', $message);
        }
    }

}