<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class TyreCheckInRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'client_id' => 'required|int|max:255',
            'car_number' => 'required|string|max:255| unique:tyres,car_number',
            'tyre_model' => 'required|string|max:255',
            'tyre_size' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'has_rim' => 'required|boolean',
            'observations' => 'nullable|string',
        ];
    }

    public function messages(){
        return [
            'client_id.required' => 'Clientul este obligatoriu.',
            'client_id.int' => 'Clientul trebuie să fie un număr întreg.',
            'client_id.max' => 'Clientul nu poate fi mai lung de 255 de caractere.',
            'car_number.required' => 'Numărul de înmatriculare este obligatoriu.',
            'car_number.string' => 'Numărul de înmatriculare trebuie să fie un șir de caractere.',
            'car_number.max' => 'Numărul de înmatriculare nu poate fi mai lung de 255 de caractere.',
            'car_number.unique' => 'Numărul de înmatriculare există deja în baza de date.',
            'tyre_model.required' => 'Modelul anvelopelor este obligatoriu.',
            'tyre_model.string' => 'Modelul anvelopelor trebuie să fie un șir de caractere.',
            'tyre_model.max' => 'Modelul anvelopelor nu poate fi mai lung de 255 de caractere.',
            'tyre_size.required' => 'Dimensiunea anvelopelor este obligatorie.',
            'tyre_size.string' => 'Dimensiunea anvelopelor trebuie să fie un șir de caractere.',
            'tyre_size.max' => 'Dimensiunea anvelopelor nu poate fi mai lung de 255 de caractere.',
            'quantity.required' => 'Cantitatea este obligatorie.',
            'quantity.integer' => 'Cantitatea trebuie să fie un număr întreg.',
            'quantity.min' => 'Cantitatea trebuie să fie mai mare de 0.',
            'has_rim.required' => 'Prezența jantei este obligatorie.',
            'has_rim.boolean' => 'Prezența jantei trebuie să fie adevărată sau falsă.',
            'observations.string' => 'Observațiile trebuie să fie un șir de caractere.',
        ];
    }
}
