<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AjoutEleveRequest extends FormRequest
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
            'nom'=>'required|max:100|min:2',
            'prenom'=>'required|max:100|min:2',
            'ine'=>'required|max:11|min:11|unique:eleves',
            'login'=>'required|max:50|min:3|unique:users', 
            'adresse'=>'required|max:1000|min:2', 
            'password'=>'required|max:40|min:8',
            'telephone_1'=>'required|max:40|min:8',
            'ville_id'=>'required|int|exists:villes,id',

        ];
    }
}