<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'car_number' => 'required|string|max:255',
            'tyre_model' => 'required|string|max:255',
            'tyre_size' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'has_rim' => 'required|boolean',
            'observations' => 'nullable|string',
        ];
    }
}
