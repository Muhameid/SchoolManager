<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Eleve extends Model
{
    use HasFactory;


    public function classe()
    {
        return $this->belongsTo(Classe::class);
    }

    public function examens()
    {
        return $this->belongsToMany(Examen::class)->withPivot('note','observation');
    }

    public function classe_options()
    {
        return $this->belongsToMany(ClasseOption::class);
    }

    public function user()
    {
        return $this->morphOne(User::class, 'usereable');
    }

    public function supprimableEleve(){
        $existe = Eleve::where('id', $this->id)->whereHas('classe')->exists();
        if($existe){
            return false;
        }
        return true;
    }
}
