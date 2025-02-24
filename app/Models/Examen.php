<?php

namespace App\Models;

use App\Models\Professeur;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Examen extends Model
{
    use HasFactory;
    protected $fillable = ['lien'];


    public function matiere()
    {
        return $this->belongsTo(Matiere::class, 'matiere_id');
    }
    

    public function eleves()
    {
        return $this->belongsToMany(Eleve::class)->withPivot('note','observation');
    }
    public function professeur()
    {
        return $this->belongsTo(Professeur::class);
    }
}
