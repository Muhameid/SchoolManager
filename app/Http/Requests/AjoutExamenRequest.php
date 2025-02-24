<?php

namespace App\Http\Requests;

use App\Models\Professeur;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class AjoutExamenRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    private $prof;
    public function authorize()
    {
        if($this->has('professeur_id') && is_numeric($this->input('professeur_id'))){
            $prof=Professeur::where('id', '=', $this->input('professeur_id') )->withWhereHas('user')->first();
            if(is_object($prof) && $prof->user->id==Auth::user()->id){
                $this->prof=$prof;
                return true;
            }
        }
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name'=>'required|max:50|min:2',
            'sujet'=>'required|max:50|min:2',
            'coefficient'=>'required',
            'lien' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'professeur_id'=>'required|exists:professeurs,id',
            'matiere_id'=>'required|exists:matieres,id',
     

        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $matiere_id = $this->input('matiere_id');
            $prof_id = $this->input('professeur_id');
            $toto=$this->prof->matieres()->where('id', '=', $matiere_id)->exists();
            if (!$toto) {
                $validator->errors()->add('professeur_id', "Le prof est pas habilité a enségné la matiere");
            }
        });
    }
}
