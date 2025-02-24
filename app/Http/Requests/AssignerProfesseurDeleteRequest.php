<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;  // Ajout de l'importation du trait Rule

class AssignerProfesseurDeleteRequest extends FormRequest
{
    /**
     * Détermine si l'utilisateur est autorisé à faire cette requête.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // Autoriser la requête (tu peux mettre une logique ici selon tes besoins)
    }

    /**
     * Récupérer les règles de validation qui s'appliquent à la requête.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'matiere_id' => [
                'required', // Par exemple, 'required' pour s'assurer que 'matiere_id' est passé dans la requête
                Rule::exists('matieres', 'id'), // Utilisation de la règle 'exists' pour vérifier l'existence de la matière
            ],
            'id_classe' => [
                'required', 
                Rule::exists('classes', 'id'), // Vérifie si 'id_classe' existe dans la table 'classes'
            ],
        ];
    }
}
