<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use App\Repositories\EleveTenantRepositoryInterface;

class EleveTenantController extends Controller
{
    private $eleve_interface;

    public function __construct()
    {
        $this->eleve_interface=App::make(EleveTenantRepositoryInterface::class);
    }

    public function listeProfSelonEleve(){   

        $eleve = auth()->user()->usereable;
        if (!$eleve || !$eleve->classe || !$eleve->classe->filiere) {
            abort(403, "Accès interdit : Cette page n'est pas accessible, vérifie avec l'administrateur.");
        }

        $interface = $this->eleve_interface;
        $result = $interface->listeProfesseursPourEleve(Auth::user()->id);
        $resultOption = $interface->listeProfesseursPourEleveOption(Auth::user()->id);
        return view('eleves.listeProfSelonEleve', ['result'=>$result], ['resultOption'=>$resultOption]);
    }
    
    public function voirMesNotes(){
        
        $eleve = auth()->user()->usereable;
        if (!$eleve || !$eleve->classe || !$eleve->classe->filiere) {
            abort(403, "Accès interdit : Cette page n'est pas accessible, vérifie avec l'administrateur.");
        }

        $interface = $this->eleve_interface;
        $notes = $interface->recupereNotes(Auth::user()->id);
        $notesOption = $interface->recupereNotesOption(Auth::user()->id);
        $moyennesMatiere = $interface->moyenneParMatiere(Auth::user()->id);
        $moyenneGenerale = $interface->moyenneGeneraleParFiliere(Auth::user()->id);
        $moyenneGeneraleAvecOption = $interface->moyenneGeneraleAvecOption(Auth::user()->id);
        $moyenneExamensFiliere = $interface->moyenneExamensFiliere(Auth::user()->id);
        $moyenneExamensOption = $interface->moyenneExamensOption(Auth::user()->id);

        
        return view('eleves.mesNotes', [
            'notes' => $notes,
            'notesOption' => $notesOption,
            'moyennesMatiere' => $moyennesMatiere,
            'moyenneGenerale' => $moyenneGenerale,
            'moyenneGeneraleAvecOption' =>$moyenneGeneraleAvecOption,
            'moyenneExamensFiliere' => $moyenneExamensFiliere,
            'moyenneExamensOption' => $moyenneExamensOption
        ]);
    }

}