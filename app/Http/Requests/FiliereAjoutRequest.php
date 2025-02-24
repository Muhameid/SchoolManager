<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class FiliereAjoutRequest extends FormRequest
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
            'nom' => 'required|max:50|min:2',  // Nom de la filière, entre 2 et 50 caractères
            'niveau' => 'required|integer|min:1|max:5',  // Niveau de la filière, entre 1 et 5
            'description' => 'required|string|max:255',  // Description de la filière, maximum 255 caractères
        ];
    }
}
