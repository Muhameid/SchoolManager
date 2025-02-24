<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classe extends Model
{
    use HasFactory;

    public function eleves()
    {
        return $this->hasMany(Eleve::class);
    }

    public function filiere(){
        return $this->belongsTo(Filiere::class,'filiere_id', 'id');
    }
    public function matieres()
    {
        return $this->belongsToMany(Matiere::class,'classe_matiere','classe_id','matiere_id');
    }
    public function professeurs()
    {
        return $this->belongsToMany(Professeur::class,'classe_matiere','classe_id', 'professeur_id');
    }
    
    public function delete(){
        if($this->supprimable()){
            return parent::delete();
        }
        return false;
    }
    
    public function supprimable(){
        $existe = Classe::where('id', $this->id)->whereHas('eleves')->exists();
        if($existe){
            return false;
        }
        return true;
    }


}