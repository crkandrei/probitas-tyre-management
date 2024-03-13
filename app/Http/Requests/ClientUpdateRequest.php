<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'telephone' => 'required|string|max:255',
            'car_number' => 'required|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Numele este obligatoriu.',
            'name.string' => 'Numele trebuie să fie un șir de caractere.',
            'name.max' => 'Numele nu poate fi mai lung de 255 de caractere.',
            'telephone.required' => 'Telefonul este obligatoriu.',
            'telephone.string' => 'Telefonul trebuie să fie un șir de caractere.',
            'telephone.max' => 'Telefonul nu poate fi mai lung de 255 de caractere.',
            'car_number.required' => 'Numărul de înmatriculare este obligatoriu.',
            'car_number.string' => 'Numărul de înmatriculare trebuie să fie un șir de caractere.',
            'car_number.max' => 'Numărul de înmatriculare nu poate fi mai lung de 255 de caractere.',
        ];
    }

}
