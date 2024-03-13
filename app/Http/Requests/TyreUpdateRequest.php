<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;


class TyreUpdateRequest extends FormRequest
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
            'model' => 'required|string',
            'size' => 'required|string',
            'observations' => 'nullable|string',
            'quantity' => 'required|int',
            'hasRim' => 'required|boolean',
        ];
    }

    public function messages()
    {
        return [
            'model.required' => 'Modelul este obligatoriu.',
            'model.string' => 'Modelul trebuie să fie un șir de caractere.',
            'size.required' => 'Dimensiunea este obligatorie.',
            'size.string' => 'Dimensiunea trebuie să fie un șir de caractere.',
            'observations.string' => 'Observațiile trebuie să fie un șir de caractere.',
            'quantity.required' => 'Cantitatea este obligatorie.',
            'quantity.int' => 'Cantitatea trebuie să fie un număr întreg.',
            'hasRim.required' => 'Prezența jantei este obligatorie.',
            'hasRim.boolean' => 'Prezența jantei trebuie să fie adevărată sau falsă.',
        ];
    }

}
