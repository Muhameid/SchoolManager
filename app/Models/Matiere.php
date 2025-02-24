<?php

namespace App\Models;

use App\Models\Filiere;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Matiere extends Model
{
    use HasFactory;

    public function filieres()
    {
        return $this->belongsToMany(Filiere::class)->withPivot('coefficient','professeur_id')->withTimestamps();
    }
    public function professeurs()
    {
        return $this->belongsToMany(Professeur::class, 'professeur_matiere', 'matiere_id', 'professeur_id');
    }
    public function classeOptions()
    {
        return $this->hasMany(ClasseOption::class);
    }
    public function classes()
    {
        return $this->belongsToMany(Classe::class, 'classe_id');
    }

    public function examens()
    {
        return $this->hasMany(Examen::class);
    }


    public function supprimableMatiere(){
        $existe = Matiere::where('id', $this->id)->whereHas('professeurs')->exists();
        if($existe){
            return false;
        }
        return true;
    }

        public function estResponsableParProfesseur($professeurId)
    {
        return $this->filieres()
                    ->wherePivot('professeur_id', $professeurId)
                    ->exists();
    }

} 