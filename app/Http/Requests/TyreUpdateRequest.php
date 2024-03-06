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

}
