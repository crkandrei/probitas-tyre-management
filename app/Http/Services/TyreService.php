<?php

namespace App\Http\Services;


use App\Models\Tyre;
use Illuminate\Http\JsonResponse;


class TyreService
{

    // Define constants for tyre age periods
    const AGE_PERIODS = [
        '0_6' => '0-6 luni',
        '6_1' => '6 luni-1 an',
        '1_2' => '1-2 ani',
        '2+' => '>2 ani',
    ];

    public function getTyreAgeStats(): JsonResponse
    {
        $now = now();
        $tyres = Tyre::query()
            ->where('status', '!=', 2) // Exclude tyres with status 2
            ->whereHas('client', function ($query) {
                $query->whereNull('deleted_at'); // Exclude tyres linked to soft deleted clients
            })
            ->get(['created_at']) // Only need the created_at field for this calculation
            ->map(function ($tyre) use ($now) {
                $ageInMonths = $now->diffInMonths($tyre->created_at);
                if ($ageInMonths <= 6) {
                    return self::AGE_PERIODS['0_6'];
                } elseif ($ageInMonths <= 12) {
                    return self::AGE_PERIODS['6_1'];
                } elseif ($ageInMonths <= 24) {
                    return self::AGE_PERIODS['1_2'];
                } else {
                    return self::AGE_PERIODS['2+'];
                }
            })
            ->groupBy(function ($age) {
                return $age; // Group by the age range
            })
            ->map->count(); // Count the number of tyres in each group

        return response()->json([
            'ages' => array_values(self::AGE_PERIODS),
            'counts' => [
                $tyres->get(self::AGE_PERIODS['0_6'], 0),
                $tyres->get(self::AGE_PERIODS['6_1'], 0),
                $tyres->get(self::AGE_PERIODS['1_2'], 0),
                $tyres->get(self::AGE_PERIODS['2+'], 0),
            ],
        ]);
    }
}
