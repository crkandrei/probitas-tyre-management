<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Numele este obligatoriu.',
            'name.string' => 'Numele trebuie să fie un șir de caractere.',
            'name.max' => 'Numele nu poate fi mai lung de 255 de caractere.',
            'email.required' => 'Adresa de email este obligatorie.',
            'email.string' => 'Adresa de email trebuie să fie un șir de caractere.',
            'email.lowercase' => 'Adresa de email trebuie să fie scrisă cu litere mici.',
            'email.email' => 'Adresa de email trebuie să fie validă.',
            'email.max' => 'Adresa de email nu poate fi mai lungă de 255 de caractere.',
            'email.unique' => 'Adresa de email este deja folosită.',
        ];
    }
}
