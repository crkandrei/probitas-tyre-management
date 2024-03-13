<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientAddRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'telephone' => 'nullable|string|max:255'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Numele este obligatoriu.',
            'name.string' => 'Numele trebuie să fie un șir de caractere.',
            'name.max' => 'Numele nu poate fi mai lung de 255 de caractere.',
            'telephone.string' => 'Telefonul trebuie să fie un șir de caractere.',
            'telephone.max' => 'Telefonul nu poate fi mai lung de 255 de caractere.',
        ];
    }
}
