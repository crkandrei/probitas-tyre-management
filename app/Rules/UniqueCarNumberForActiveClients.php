<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;


class UniqueCarNumberForActiveClients implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Query to check if a tyre with the given car_number is linked to an active (not soft-deleted) client
        $exists = DB::table('tyres')
            ->join('clients', 'tyres.client_id', '=', 'clients.id')
            ->where('tyres.car_number', $value)
            ->whereNull('clients.deleted_at') // This ensures we ignore soft-deleted clients
            ->exists();

        // If a record is found, it means the car_number is not unique among active clients
        if ($exists) {
            $fail('Numarul de inmatriculatre exista deja in baza de date.')->translate();
        }
    }
}
