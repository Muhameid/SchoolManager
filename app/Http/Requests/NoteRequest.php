<?php

namespace App\Http\Requests;

use App\Http\Requests\NoteRequest;
use Illuminate\Foundation\Http\FormRequest;

class NoteRequest extends FormRequest
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
            'note' => 'nullable|array', 
            'note.*' => 'nullable|numeric|min:0|max:20',
            'observation' => 'array',
            'observation.*' => 'string|min:3|max:240',
        ];
    }
}