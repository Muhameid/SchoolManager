<?php

namespace App\Repositories;

use Exception;
use App\Models\User;
use App\Models\Eleve;
use App\Models\Examen;

class EleveTenantRepository implements EleveTenantRepositoryInterface{
    public function listeProfesseursPourEleve(int $userId){
        $eleve = Eleve::with(['classe' => function ($query) {
            $query->with(['filiere.matieres' => function ($query) {
                $query->withPivot('coefficient');
            }]);
            $query->with(['professeurs' => function ($query) {
                $query->withPivot('matiere_id', 'professeur_id');
                $query->with('user');
            }]);
        }])
        ->whereHas('user', function ($query) use ($userId) {
            $query->where('id', '=', $userId);
        })
        ->first();

        $professeurs = [];

        if ($eleve && $eleve->classe) {
            foreach ($eleve->classe->filiere->matieres as $matiere) {
                $professeurTrouve = false;
                $professeursAssocies = [];

                foreach ($eleve->classe->professeurs as $professeur) {
                    if ($matiere->pivot->matiere_id == $professeur->pivot->matiere_id) {
                        $professeursAssocies[] = [
                            'professeur' => $professeur->user->prenom . ' ' . $professeur->user->nom,
                            'matiere' => $matiere->nom,
                            'coefficient' => $matiere->pivot->coefficient,
                        ];
                        $professeurTrouve = true;
                    }
                }

                if (!$professeurTrouve) {
                    $professeursAssocies[] = [
                        'professeur' => 'Aucun professeur',
                        'matiere' => $matiere->nom,
                        'coefficient' => $matiere->pivot->coefficient,
                    ];
                }

                $professeurs = array_merge($professeurs, $professeursAssocies);
            }
        }

        return $professeurs;
    }

    public function listeProfesseursPourEleveOption(int $userId)
    {
        $eleve = Eleve::with([
            'classe_options' => function ($query) {
                $query->with(['matiere', 'professeur.user']);
            }
        ])
        ->whereHas('user', function ($query) use ($userId) {
            $query->where('id', '=', $userId);
        })
        ->first();
    
        $professeurs = [];
    
        if ($eleve && $eleve->classe_options) {
            foreach ($eleve->classe_options as $classeOption) {
                $professeurTrouve = false;
                $professeursAssocies = [];
    
                $matiere = $classeOption->matiere;
                $professeur = $classeOption->professeur;
        
    
                if ($matiere && $professeur) {
                    $professeursAssocies[] = [
                        'professeurOption' => $professeur->user->prenom . ' ' . $professeur->user->nom,
                        'nomOption' => $classeOption->nom,
                        'matiereOption' => $matiere->nom,
                        'coefficientOption' => $classeOption->coefficient ?? 'N/A', 
                        
                    ];
                    $professeurTrouve = true;
                }
    
                if (!$professeurTrouve && isset($matiere)) {
                    $professeursAssocies[] = [
                        'professeurOption' => 'Aucun professeur',
                        'nomOption' => $classeOption->nom,
                        'matiereOption' => $matiere->nom,
                        'coefficientOption' => $classeOption->coefficient ?? 'N/A',
                    ];
                }
    
                $professeurs = array_merge($professeurs, $professeursAssocies);
            }
        }
    
        return $professeurs;
    }
        


    public function recupereNotes(int $userId){

    $eleve = Eleve::with(['classe.filiere.matieres', 'examens' => function ($query) {
        $query->withPivot('note');
        $query->with('matiere');
    }])
    ->whereHas('user', function ($query) use ($userId) {
        $query->where('id', '=', $userId);
    })
    ->first();

    $matieresFiliere = $eleve->classe->filiere->matieres;
    $examensParMatiere = $eleve->examens->groupBy('matiere_id');

    $notes = $matieresFiliere->mapWithKeys(function ($matiere) use ($examensParMatiere) {
        return [$matiere->nom => $examensParMatiere->get($matiere->id, collect())];
    });

    return $notes;
    }

    public function moyenneParMatiere(int $userId)
    {
        $eleve = Eleve::with(['examens' => function ($query) {
            $query->withPivot('note');
            $query->with('matiere');
        }])
        ->whereHas('user', function ($query) use ($userId) {
            $query->where('id', '=', $userId);
        })
        ->first();

        $moyennes = [];

        foreach ($eleve->examens as $examen) {
            $matiereNom = $examen->matiere->nom;
            $note = $examen->pivot->note;
            $coefficient = $examen->coefficient;

            if (!isset($moyennes[$matiereNom])) {
                $moyennes[$matiereNom] = [
                    'total_notes' => 0,
                    'total_coefficients' => 0,
                ];
            }

            $moyennes[$matiereNom]['total_notes'] += $note * $coefficient;
            $moyennes[$matiereNom]['total_coefficients'] += $coefficient;
        }

        foreach ($moyennes as $matiereNom => $data) {
            $moyennes[$matiereNom] = $data['total_coefficients'] > 0
                ? $data['total_notes'] / $data['total_coefficients']
                : null;
        }

        return $moyennes;
    }
    public function moyenneGeneraleParFiliere(int $userId)
    {

        $eleve = Eleve::with(['examens' => function ($query){
            $query->withPivot('note');
            $query->with('matiere');
        }, 'classe.filiere.matieres' => function ($query){
            $query->withPivot('coefficient');
        }])
        ->whereHas('user', function ($query) use ($userId){
            $query->where('id', '=', $userId);
        })
        ->first();
    
        $moyennes = [];
        $totalGlobal = 0;
        $totalCoefficientsGlobaux = 0;
    
        foreach ($eleve->examens as $examen){
            $matiereNom = $examen->matiere->nom;
            $note = $examen->pivot->note;
            $coefficient = $examen->coefficient;

            if (!isset($moyennes[$matiereNom])){
                $moyennes[$matiereNom] = [
                    'total_notes' => 0,
                    'total_coefficients' => 0,
                ];
            }
 
            $moyennes[$matiereNom]['total_notes'] += $note * $coefficient;
            $moyennes[$matiereNom]['total_coefficients'] += $coefficient;
        }

        foreach ($moyennes as $matiereNom => $data){
            $moyennes[$matiereNom] = $data['total_coefficients'] > 0
                ? $data['total_notes'] / $data['total_coefficients']
                : null;
        }
        $totalGlobal=0;
        $totalCoefficientsGlobaux=0;
        foreach ($eleve->classe->filiere->matieres as $matiere){
            if (isset($moyennes[$matiere->nom])){
                $matiereCoefficientFiliere = $matiere->pivot->coefficient;
                $totalGlobal += $moyennes[$matiere->nom] * $matiereCoefficientFiliere;
                
                $totalCoefficientsGlobaux += $matiereCoefficientFiliere;
                // dd($matiereCoefficientFiliere, $totalGlobal,  $totalCoefficientsGlobaux);
                
            }
        }
        
        $moyenneFinale = ($totalCoefficientsGlobaux != 0) ? ($totalGlobal / $totalCoefficientsGlobaux) : 0;

       
        return [
            'moyennes_par_matiere' => $moyennes,
            'moyenne_finale' => round($moyenneFinale, 2)
        ];
    }
    

    public function recupereNotesOption(int $userId)
    {
        $user = User::find($userId);
    
        if (!$user || !$user->usereable) {
            return collect(['Eleve non-trouvé' => 'Élève non trouvé']);
        }
    
        $eleve = $user->usereable;
    
        if ($eleve->classe_options->isEmpty()) {
            return collect(['Aucune classe option' => 'Aucune option de classe disponible']);
        }
    
        
        $matieresOption = $eleve->classe_options->map(function ($classeOption) {
            return $classeOption->matiere;
        });
        // dump($matiere_ids=$matieresOption->pluck('id'));
        // $examen=Examen::withWhereHas('eleves', function($query) use ($eleve){
        //     $query->where('eleve_id',$eleve->id);
        // })->whereIn('matiere_id',$matiere_ids)->get();
        // dd($examen);
        
        $notes = $matieresOption->mapWithKeys(function ($matiere) use ($eleve) {
            $examens = $eleve->examens()->where('matiere_id', $matiere->id)->get();
            // Si des examens existent pour la matière, on les retourne dans une collection, sinon on retourne une collection vide
            return [
                $matiere->nom => $examens->isEmpty() ? collect() : $examens,
            ];
        });

        return $notes;
    }

    public function moyenneExamensFiliere(int $userId, ?int $examenId = null) {
        $eleve = Eleve::with('classe.filiere.matieres', 'classe.eleves')
            ->whereHas('user', function ($query) use ($userId) {
                $query->where('id', '=', $userId);
            })
            ->first();
    
        $result = [];
    
        if ($eleve && $eleve->classe && $eleve->classe->filiere) {
            $filiere = $eleve->classe->filiere;
            $elevesFiliereIds = $eleve->classe->eleves->pluck('id')->toArray();
    
            foreach ($filiere->matieres as $matiere) {
                $examensQuery = Examen::with(['eleves' => function ($query) use ($elevesFiliereIds) {
                    $query->whereIn('eleve_id', $elevesFiliereIds);
                }])->where('matiere_id', $matiere->id);
    

                if ($examenId) {
                    $examensQuery->where('id', $examenId);
                }
    
                $examens = $examensQuery->get();
    
                foreach ($examens as $examen) {
                    $notes = $examen->eleves->pluck('pivot.note')->filter(function ($note) {
                        return !is_null($note);
                    });
                
                    $noteMaximale = $notes->isEmpty() ? 'N/A' : round($notes->max(), 2);
                    $noteMinimale = $notes->isEmpty() ? 'N/A' : round($notes->min(), 2);
    
                    $result[$matiere->nom][] = [
                        'id'      => $examen->id,
                        'examen'  => $examen->name,
                        'sujet'   => $examen->sujet,
                        'moyenne' => round($examen->eleves->avg('pivot.note'), 2),
                        'max'     => $noteMaximale,
                        'min'     => $noteMinimale,
                    ];
                }
            }
        }
    
        return $result;
    }
    
    public function moyenneExamensOption(int $userId, ?int $examenId = null) {
        $eleve = Eleve::with('classe_options.matiere', 'classe_options.professeur.user', 'classe_options.eleves')
            ->whereHas('user', function ($query) use ($userId) {
                $query->where('id', '=', $userId);
            })
            ->first();

        $result = [];

        if ($eleve && $eleve->classe_options) {
            foreach ($eleve->classe_options as $classeOption) {
                $matiere = $classeOption->matiere;
                $examensQuery = Examen::with(['eleves' => function ($query) use ($classeOption) {
                    $query->whereHas('classe_options', function ($q) use ($classeOption) {
                        $q->where('classe_option_id', $classeOption->id);
                    });
                }])->where('matiere_id', $matiere->id);

                if ($examenId) {
                    $examensQuery->where('id', $examenId);
                }

                $examens = $examensQuery->get();

                foreach ($examens as $examen) {
                    $notes = $examen->eleves->pluck('pivot.note')->filter(function ($note) {
                        return !is_null($note);
                    });

                    //Calcul de la moyenne
                    $moyenne = $notes->avg();
                    $noteMaximale = $notes->isEmpty() ? 'N/A' : round($notes->max(), 2);
                    $noteMinimale = $notes->isEmpty() ? 'N/A' : round($notes->min(), 2);

                    $result[$matiere->nom][] = [
                        'id' => $examen->id,
                        'examen' => $examen->name,
                        'sujet' => $examen->sujet,
                        'moyenne' => $moyenne ? round($moyenne, 2) : 'N/A', 
                        'max' => $noteMaximale,
                        'min' => $noteMinimale,
                    ];
                }
        }
        }

        return $result;
    }
    

    public function moyenneGeneraleAvecOption(int $userId)
    {
        $eleve = Eleve::with(['classe.filiere.matieres' => function ($query) {
            $query->withPivot('coefficient');
        }, 'classe_options.matiere', 'examens' => function ($query) {
            $query->with('matiere');
            $query->withPivot('note');
        }])
        ->whereHas('user', function ($query) use ($userId) {
            $query->where('id', '=', $userId);
        })
         ->first();
        
            if (!$eleve || !$eleve->classe || !$eleve->classe->filiere) {
              return null; 
           }
    
            $totalPoints = 0;
             $totalCoefficients = 0;
        
        
          
             foreach ($eleve->classe->filiere->matieres as $matiere) {
               $totalNotesMatiere = 0;
               $nombreNotesMatiere = 0;
    
               foreach ($eleve->examens as $examen) {
                     if ($examen->matiere_id == $matiere->id) {
                           $totalNotesMatiere += $examen->pivot->note;
                           $nombreNotesMatiere++;
                      }
                 }
    
                 $moyenneMatiere = ($nombreNotesMatiere > 0) ? ($totalNotesMatiere / $nombreNotesMatiere) : null;
                 if($moyenneMatiere !== null){
                      $matiereCoefficientFiliere = $matiere->pivot->coefficient;
                       $totalPoints += $moyenneMatiere * $matiereCoefficientFiliere;
                       $totalCoefficients += $matiereCoefficientFiliere;
                    }
             }
  
             
             foreach ($eleve->classe_options as $classeOption) {
               $matiereOption = $classeOption->matiere;
               $totalNotesOption = 0;
                $nombreNotesOption = 0;
                 foreach ($eleve->examens as $examen) {
                     if ($examen->matiere_id == $matiereOption->id){
                        $totalNotesOption += $examen->pivot->note;
                         $nombreNotesOption++;
                      }
                }
    
              $moyenneOption = ($nombreNotesOption > 0) ? ($totalNotesOption / $nombreNotesOption) : null;
                // Ne prendre en compte que si au moins un examen existe dans cette matière
                if ($moyenneOption !== null){
                    $matiereCoefficientOption = 2; // Coefficient fixe de 2 pour les matières d'option
                   $totalPoints += $moyenneOption * $matiereCoefficientOption;
                     $totalCoefficients += $matiereCoefficientOption;
                }
            }
        
         $moyenneGenerale = ($totalCoefficients > 0) ? ($totalPoints / $totalCoefficients) : 0;
           return round($moyenneGenerale, 2);
    
    }
    // public function moyenneExamensFiliere(int $userId) {

    //     $eleve = Eleve::with('classe.filiere.matieres', 'classe.eleves')
    //         ->whereHas('user', function ($query) use ($userId) {
    //             $query->where('id', '=', $userId);
    //         })
    //         ->first();
    
    //     $result = [];
        
    //     if ($eleve && $eleve->classe && $eleve->classe->filiere) {
    //         $filiere = $eleve->classe->filiere;
    //         $elevesFiliereIds = $eleve->classe->eleves->pluck('id')->toArray();
            
    //         foreach ($filiere->matieres as $matiere) {

    //             $examens = Examen::with(['eleves' => function ($query) use ($elevesFiliereIds) {
    //                     $query->whereIn('eleve_id', $elevesFiliereIds); 
    //                 }])
    //                 ->where('matiere_id', $matiere->id)
    //                 ->get();
    
    //             $moyenneMatiere = 0;
    //             $totalExams = 0;
    
    //             foreach ($examens as $examen) {
    //                 $total = 0;
    //                 $count = 0;
    
    //                 foreach ($examen->eleves as $eleveExamen) {
    //                     if (!is_null($eleveExamen->pivot->note)) {
    //                         $total += $eleveExamen->pivot->note;
    //                         $count++;
    //                     }
    //                 }
    
    //                 $moyenneExamen = $count > 0 ? round($total / $count, 2) : 'N/A';

    //                 if ($moyenneExamen !== 'N/A') {
    //                     $moyenneMatiere += $moyenneExamen;
    //                     $totalExams++;
    //                 }

    //                 $result[$matiere->nom][] = [
    //                     'examen'  => $examen->name,
    //                     'sujet'   => $examen->sujet,
    //                     'moyenne' => $moyenneExamen,
    //                 ];
    //             }

    //             if ($totalExams > 0) {
    //                 $result[$matiere->nom]['moyenne_generale'] = round($moyenneMatiere / $totalExams, 2);
    //             } else {
    //                 $result[$matiere->nom]['moyenne_generale'] = 'N/A';
    //             }
    //         }
    //     }
    
    //     return $result;
    // }
    
}
