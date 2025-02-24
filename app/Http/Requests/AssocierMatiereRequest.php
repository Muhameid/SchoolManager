<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssocierMatiereRequest extends FormRequest
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
            'matieres' => ['nullable', 'array'], // Peut être vide
            'matieres.*' => ['integer', 'exists:matieres,id'], // Vérifie que chaque matière cochée existe

            'coefficient' => ['nullable', 'array'], // Peut être vide
            'coefficient.*' => ['integer', 'min:1', 'max:40'], // Vérifie que chaque coefficient est valide

            'professeur' => ['nullable', 'array'], // Peut être vide
            'professeur.*' => ['nullable','integer', 'exists:professeurs,id' ], // Vérifie que chaque coefficient est valide
    
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $matieres = $this->input('matieres', []);
            $coefficients = $this->input('coefficient', []);

            // Vérifie que le nombre de matières cochées est égal au nombre de coefficients envoyés
            if (count($matieres) !== count($coefficients)) {
                $validator->errors()->add('coefficient', "Le nombre de matières sélectionnées doit être égal au nombre de coefficients fournis.");
            }

            // Vérifie que chaque matière cochée a un coefficient et vice versa
            foreach ($matieres as $matiere_id) {
                if (!isset($coefficients[$matiere_id])) {
                    $validator->errors()->add('coefficient', "Le coefficient pour la matière ID {$matiere_id} est requis.");
                }
            }

            foreach ($coefficients as $matiere_id => $coef) {
                if (!in_array($matiere_id, $matieres)) {
                    $validator->errors()->add('matieres', "Un coefficient a été fourni pour une matière non sélectionnée (ID {$matiere_id}).");
                }
            }
        });
    }


    public function messages()
    {
        return [
            'matieres.*.integer' => 'L\'identifiant de la matière doit être un nombre entier.',
            'matieres.*.exists' => 'Une matière sélectionnée est invalide.',

            'coefficient.*.integer' => 'Le coefficient doit être un nombre entier.',
            'coefficient.*.min' => 'Le coefficient doit être au minimum 1.',
            'coefficient.*.max' => 'Le coefficient ne doit pas dépasser 40.',
        ];
    }
}
