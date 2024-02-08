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
}
