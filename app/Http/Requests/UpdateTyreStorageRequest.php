<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTyreStorageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'row' => 'required|int',
            'column' => 'nullable|int',
            'shelf' => 'nullable|int',
            'observations' => 'nullable|string'
        ];
    }

    public function messages(){
        return [
            'row.required' => 'Rândul este obligatoriu.',
            'row.int' => 'Rândul trebuie să fie un număr întreg.',
            'column.int' => 'Coloana trebuie să fie un număr întreg.',
            'shelf.int' => 'Raftul trebuie să fie un număr întreg.',
            'observations.string' => 'Observațiile trebuie să fie un șir de caractere.'
        ];
    }
}
