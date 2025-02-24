<?php

namespace App\Models;

use App\Models\Examen;
use App\Models\Matiere;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Professeur extends Model
{
    use HasFactory;
    public function classeOptions()
    {
        return $this->hasMany(ClasseOption::class);
    }


    public function matieres()
    {
        return $this->belongsToMany(Matiere::class, 'professeur_matiere','professeur_id', 'matiere_id');
    }


    public function user()
    {
        return $this->morphOne(User::class, 'usereable');
    }
    public function classes()
    {
        return $this->belongsToMany(Classe::class, 'classe_matiere','professeur_id', 'classe_id');
    }
    public function examens()
    {
        return $this->hasMany(Examen::class);
    }
}
