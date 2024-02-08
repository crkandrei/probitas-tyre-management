<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientAddRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'telephone' => 'required|string|max:255'
        ];
    }
}
