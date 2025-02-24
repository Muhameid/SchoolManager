<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class ClasseOption extends Model {
    use HasFactory;

    public function professeur()
    {
        return $this->belongsTo(Professeur::class, 'professeur_id', 'id');
    }

    public function matiere()
    {
        return $this->belongsTo(Matiere::class, 'matiere_id', 'id');
    }

    public function eleves()
    {
        return $this->belongsToMany(Eleve::class);
    }
    public function deleteOption(){
        if($this->supprimable()){
            return parent::delete();
        }
        return false;
    }
    
    public function supprimableOption(){
        $existe = ClasseOption::where('id', $this->id)->whereHas('eleves')->exists();
        if($existe){
            return false;
        }
        return true;
    }

}

