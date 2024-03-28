<?php

namespace App\Http\Services;


use App\Events\TyreCheckedIn;
use App\Models\History;
use App\Models\Tyre;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;


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
            ->get(['checkin_date']) // Only need the checkin_date field for this calculation
            ->map(function ($tyre) use ($now) {
                $ageInMonths = $now->diffInMonths($tyre->checkin_date);
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

    public function update(Tyre $tyre, mixed $validated): void
    {
        DB::transaction(function () use ($tyre, $validated) {
            // Check if 'checkin_date' is provided and has changed.
            if (isset($validated['checkin_date']) && $tyre->checkin_date != $validated['checkin_date']) {
                $oldCheckinDate = $tyre->checkin_date;
                $newCheckinDate = $validated['checkin_date'];

                // Proceed to update the tyre
                $tyre->update($validated);

                // Update the corresponding history record for check-in action.
                $this->updateCheckInDateHistory($tyre->id, $newCheckinDate);
            } elseif (isset($validated['client_id']) && $tyre->client_id != $validated['client_id']) {
                // If 'client_id' has changed, update the tyre and create a new history record for check-in action.
                $tyre->update($validated);

                $this->updateClientHistory($tyre->id, $validated['client_id']);
            }
            else {
                // If 'checkin_date' hasn't changed or isn't provided, just update the tyre without touching the history.
                $tyre->update($validated);
            }
        });
    }

    private function updateCheckInDateHistory(mixed $tyreId, mixed $newCheckInDate)
    {
        $history = History::where('tyre_id', $tyreId)
            ->where('action', TyreCheckedIn::ACTION)
            ->orderBy('action_date', 'desc') // Order by action_date to get the most recent first
            ->first();

        if ($history) {
            $history->action_date = $newCheckInDate;
            $history->save();
        }
    }

    private function updateClientHistory(mixed $id, mixed $client_id)
    {
        $history = History::where('tyre_id', $id);
        $history->update(['client_id' => $client_id]);
    }
}
