<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssignerProfesseurRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'id_classe' => 'required|int|exists:classes,id',
            'professeur_id' => 'required|int|exists:professeurs,id',
            'matiere_id' => 'required|int|exists:matieres,id', 
        ];
    }
}
