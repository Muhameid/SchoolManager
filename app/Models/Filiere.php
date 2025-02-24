<?php

namespace App\Models;

use App\Models\Matiere;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Filiere extends Model
{
    public function matieres()
    {
        return $this->belongsToMany(Matiere::class)->withPivot('coefficient')->withTimestamps();
    }
    public function classes()
    {
        return $this->hasMany(Classe::class);
        
    }
    use HasFactory;
}