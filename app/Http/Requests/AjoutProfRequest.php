<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AjoutProfRequest extends FormRequest
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
            'identifiant' => 'required|string|unique:professeurs,identifiant|max:20|min:4',
            'login' => 'required|string|unique:users,login|max:255',
            'nom'=>'required|max:100|min:2',
            'prenom'=>'required|max:100|min:2',
            'login'=>'required|max:50|min:3|unique:users', 
            'adresse'=>'required|max:1000|min:2', 
            'password'=>'required|max:40|min:8',
            'telephone_1'=>'required|max:40|min:8',
            'ville_id'=>'required|int|exists:villes,id',
        
        ];
    }
}