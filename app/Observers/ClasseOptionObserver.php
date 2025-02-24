<?php

namespace App\Observers;

use App\Models\Eleve;
use App\Models\Professeur;
use App\Models\ClasseOption;

class ClasseOptionObserver
{
    private function verifProfesseurMatiere(ClasseOption $classeOption){
        $professeur_id=$classeOption->professeur_id;
        if(!is_null($professeur_id)){
            $matiere_id=$classeOption->matiere_id;
            $professeur=Professeur::where('id','=',$professeur_id)
            ->whereHas('matieres', function($query) use($matiere_id){
                $query->where('id','=',$matiere_id);
            })->exists();
            return $professeur;
        }
        return true;
    }


    private function verifEleveClasseOption(ClasseOption $classeOption){
        $eleve_id=$classeOption->eleve_id;
        if(!is_null($eleve_id)){
            $matiere_id=$classeOption->matiere_id;
            $eleve=Eleve::where('id','=',$eleve_id)
            ->whereHas('matieres', function($query) use($matiere_id){
                $query->where('id','=',$matiere_id);
            })->exists();
            return $eleve;
        }
        return true;
    }

    /**
     * Handle the ClasseOption "created" event.
     *
     * @param  \App\Models\ClasseOption  $classeOption
     * @return void
     */
    public function creating(ClasseOption $classeOption)
    {
        if(!$this->verifProfesseurMatiere($classeOption)) return false;
    }

    public function created(ClasseOption $classeOption)
    {
        //
    }

    /**
     * Handle the ClasseOption "updated" event.
     *
     * @param  \App\Models\ClasseOption  $classeOption
     * @return void
     */
    public function updating(ClasseOption $classeOption)
    {
        if(!$this->verifProfesseurMatiere($classeOption)) return false;
    }


    public function updated(ClasseOption $classeOption)
    {
        //
    }

    /**
     * Handle the ClasseOption "deleted" event.
     *
     * @param  \App\Models\ClasseOption  $classeOption
     * @return void
     */
    public function deleted(ClasseOption $classeOption)
    {
        if(!$this->verifEleveClasseOption($classeOption)) return false;

    }
    /**
     * Handle the ClasseOption "deleted" event.
     *
     * @param  \App\Models\ClasseOption  $classeOption
     * @return void
     */
    public function deleting(ClasseOption $classeOption)
    {
        if(!$this->verifEleveClasseOption($classeOption)) return false;

    }

    

    /**
     * Handle the ClasseOption "restored" event.
     *
     * @param  \App\Models\ClasseOption  $classeOption
     * @return void
     */
    public function restored(ClasseOption $classeOption)
    {
        //
    }

    /**
     * Handle the ClasseOption "force deleted" event.
     *
     * @param  \App\Models\ClasseOption  $classeOption
     * @return void
     */
    public function forceDeleted(ClasseOption $classeOption)
    {
        //
    }
}
