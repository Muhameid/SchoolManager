<?php

namespace App\Repositories;


use App\Models\Eleve;
use App\Models\Classe;
use App\Models\Examen;
use App\Models\Filiere;
use App\Models\Matiere;
use App\Models\Professeur;
use App\Http\Requests\NoteRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use App\Http\Requests\AjoutExamenRequest;
use App\Http\Requests\AssocierExamenRequest;
use App\Http\Requests\RetirerEleveExamenRequest;
use App\Repositories\ProfesseurTenantRepositoryInterface;

class ProfesseurTenantRepository implements ProfesseurTenantRepositoryInterface {
    public function listeClassesProf(int $professeur_user_id){

        $userId=$professeur_user_id;
        $professeur = Professeur::with(['examens','classes' => function ($query) {
            $query->with('eleves');
            $query->with('filiere');
            $query->withPivot('classe_id', 'matiere_id', 'professeur_id'); 
        }])->with('matieres')
        ->whereHas('user', function ($query) use ($userId) {
            $query->where('id', '=', $userId);  
        })
        ->get();  

        return $professeur;  
        
    }
    public function listeOptionsProf(int $professeur_user_id){
        $userId=$professeur_user_id;
        $professeur = Professeur::with(['classeOptions' => function ($query) {
            $query->with('eleves');
            $query->with('matiere');
        }])  
        ->whereHas('user', function ($query) use ($userId) {
            $query->where('id', '=', $userId);  
        })
        ->get();  
        return $professeur;  


    }
    public function listeResponsablesProf(int $professeur_user_id){
        $userId=$professeur_user_id;
      /*  $professeur = Professeur::with(['filieres' => function ($query) {
            $query->with('matieres');
            $query->withPivot('filiere_id', 'matiere_id', 'professeur_id');
        }])  
        ->whereHas('user', function ($query) use ($userId) {
            $query->where('id', '=', $userId);  
        })
        ->get();  
        dd($professeur);
        return $professeur;*/
        return false;
        
    }


    public function listeElevesParClasse(int $userId){
        $professeur = Professeur::whereHas('user', function ($query) use ($userId) {
            $query->where('id', '=', $userId);
            })->first();

        $eleves = Eleve::with(['user', 'classe'])
            ->whereIn('classe_id', $professeur->classes->pluck('id'))
            ->orderBy('id')
            ->get();
        return ['eleves' => $eleves];
    }



    public function listeClassesProfPourExamen(int $userId)
    {
        return Professeur::find($userId)->classes;
    }

    public function listeMatieresProf(int $userId)
    {
        $professeur = Professeur::with('matieres')->whereHas('user', function ($query) use ($userId) {
            $query->where('id', '=', $userId);
        })->first();
        
        return $professeur->matieres;
        
    }
    public function mesMatieres(int $userId)
    {
        $professeur = Professeur::with(['matieres' => function ($query) {
            $query->orderBy('nom', 'asc'); // Tri par nom de la matière par ordre croissant (ascendant)
        }, 'matieres.filieres']) 
        ->whereHas('user', function ($query) use ($userId) {
            $query->where('id', '=', $userId);
        })
        ->first();

    
        return $professeur ? $professeur->matieres : collect(); // Retourner une collection vide si aucun professeur trouvé
    }
    
        
    
    public function enregistrementExamen(AjoutExamenRequest $ajouterexamen)
{
    // Récupérer les données du formulaire
    $a = $ajouterexamen->input('name');
    $b = $ajouterexamen->input('matiere_id');
    $c = $ajouterexamen->input('sujet');
    $e = $ajouterexamen->input('lien', ''); // Récupère le lien (peut être vide)
    $f = $ajouterexamen->input('date_examen');
    $g = $ajouterexamen->input('professeur_id');

    // Gestion du fichier 'lien'
    if ($ajouterexamen->hasFile('lien')) {
        
        $examen = $this->enregistrementExamenTraitement($a, $b, $c, $e, $f, $g);

        
        $lienName = $examen->id . '.' . $ajouterexamen->lien->extension();

        
        $ajouterexamen->lien->storeAs('/examens', $lienName);

        
        $examen->update(['lien' => $lienName]);

        
        $e = $lienName;
    } else {
        $examen = $this->enregistrementExamenTraitement($a, $b, $c, $e, $f, $g);
    }

    return $examen;
}

private function enregistrementExamenTraitement(
    string $name,
    int $matiere_id,
    string $sujet,
    string $lien,
    string $date_examen,
    int $professeur_id
) {
    $matiere = Matiere::find($matiere_id);
    $examen = new Examen();
    $examen->name = $name;
    $examen->matiere_id = $matiere_id;
    $examen->sujet = $sujet;
    $examen->lien = $lien; 
    $examen->date_examen = $date_examen;
    $examen->professeur_id = $professeur_id;

    $examen->matiere()->associate($matiere);
    $examen->save();

    return $examen;
}
public function associerExamen(AssocierExamenRequest $associerexamen)
{
    $eleves_id = $associerexamen->input('eleves'); 
    $examen_id = $associerexamen->input('examen_id');

    return $this->associerExamenTraitement($eleves_id, $examen_id);
}
public function retirerExamenEleve(RetirerEleveExamenRequest $retirerexamen)
{
    $eleves = $retirerexamen->input('eleves');
    $examen_id = $retirerexamen->input('examen_id'); 
    foreach ($eleves as $eleve_id) {
        $eleve = Eleve::findOrFail($eleve_id);
        $eleve->examens()->detach($examen_id);
    }

    return true;
}

private function associerExamenTraitement(array $eleves_id, int $examen_id)
{
    $examen = Examen::find($examen_id);

    if (!$examen) {
        return false;
    }

    
    foreach ($eleves_id as $eleve_id) {
        $eleve = Eleve::find($eleve_id);
        if ($eleve) {
            $eleve->examens()->syncWithoutDetaching($examen_id);
        }
    }

    return $examen;
}


public function listeExamens(){
    $professeur=auth::user()->usereable_id;
    $examen = Examen::with('matiere')->where('professeur_id','=',$professeur)->get();
    return  $examen;
}
public function FicheExamen(int $id){
    $userid = Auth::user()->usereable_id;

    // Récupération de l'examen avec sa matière
    $examen = Examen::with('eleves')
                    ->with('matiere')
                    ->find($id);

    $matiereId = $examen->matiere_id; // Récupération de l'ID de la matière de l'examen

    // Chargement du professeur avec ses classes et classesOptions filtrées
    $professeur = Professeur::with('user')
        ->with(['classes' => function ($query) use ($matiereId) {
            $query->with('filiere')
                  ->with('eleves.user')
                  ->whereHas('matieres', function ($q) use ($matiereId) { 
                      $q->where('matiere_id', $matiereId); 
                  }) // Filtre les classes via la table pivot classe_matiere
                  ->withPivot('classe_id', 'matiere_id', 'professeur_id'); 
        }])
        ->with(['classeOptions' => function ($query) use ($matiereId) {
            $query->with('matiere')
                  ->with('eleves.user')
                  ->where('matiere_id', $matiereId); // Filtre classeOptions avec la bonne matière
        }])
        ->find($userid);

    return ['examen' => $examen, 'professeur' => $professeur];
}

public function infoEleveProfesseur(int $id){
    $eleve = Eleve::with('user')
                   ->with('examens')
                   ->find($id);
    return $eleve;

}

public function PageNote(int $id)
    {
        // Récupère l'examen avec les élèves associés
        $examen = Examen::with('eleves')->with('matiere.filieres')->find($id);
        return $examen;
    }

    public function Note(int $id, NoteRequest $request)
    {
        // Récupère l'examen avec les élèves associés
        $examen = Examen::with('eleves')->find($id);
        
        if (!$examen) {
            // Gérer l'absence de l'examen
            return null;
        }
    
        // Parcours les élèves associés à l'examen
        foreach ($examen->eleves as $eleve) {
            // Récupère la note de l'élève pour cet examen
            $note = $request->input('note.' . $eleve->id);
            $observation = $request->input('observation.' . $eleve->id);
            // Si la note existe, on la met à jour dans la table pivot
            if (is_numeric($note) && $note >= 0 && $note <= 20 && !(is_null($observation))) {
                // Met à jour la note dans la table pivot
                $examen->eleves()->updateExistingPivot($eleve->id, ['note' => $note]);
                $examen->eleves()->updateExistingPivot($eleve->id, ['observation' => $observation]);

            }
            else{
                return false;
            }
        }
    }



    public function supprimerExamen(int $id){
        $examen = Examen::with('eleves')->find($id);
        if(is_object($examen) && (count($examen->eleves)==0)){
        return $examen->delete();
        }
        return false;
    }

}


