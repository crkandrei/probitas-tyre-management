<?php
namespace App\Http\Services;

use App\Events\TyreCheckedIn;
use App\Models\Client;
use App\Models\Tyre;
use Illuminate\Support\Facades\DB;


class ClientTyreService
{
    public function checkIn($data)
    {
        $client = null;
        $tyre = null;

        DB::transaction(function () use ($data, &$client, &$tyre) {

            $client = Client::find($data['client_id']);

            $tyre = new Tyre([
                'client_id' => $client->id,
                'car_number' => $data['car_number'],
                'model' => $data['tyre_model'],
                'size' => $data['tyre_size'],
                'quantity' => $data['quantity'],
                'status' => 0,
                'hasRim' => $data['has_rim'],
                'observations' => $data['observations'],
                'checkin_date' => $data['checkin_date'] ?? now()
            ]);

            $client->tyres()->save($tyre);
        });

        if ($client && $tyre) {
            event(new TyreCheckedIn($client->id, $tyre->id, $tyre->checkin_date));
        }

        return $client;

    }
}
