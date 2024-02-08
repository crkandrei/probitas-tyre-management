<?php
namespace App\Repositories;

use App\Events\TyreCheckedOut;
use App\Models\Tyre;

class TyreRepository
{
    protected const paginationLimit = 10;

    public function searchTyres($searchTerm)
    {
        return Tyre::with('client', 'storage')
            ->where(function($query) use ($searchTerm) {
                $query->where('model', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('size', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('status', 'LIKE', "%{$searchTerm}%")
                    ->orWhereHas('client', function ($q) use ($searchTerm) {
                        $q->where('name', 'LIKE', "%{$searchTerm}%")
                            ->orWhere('telephone', 'LIKE', "%{$searchTerm}%")
                            ->orWhere('car_number', 'LIKE', "%{$searchTerm}%");
                    });
            })
            ->paginate(self::paginationLimit);
    }

    public function checkoutTyre(Tyre $tyre)
    {
        $tyre->update([
            'status' => 2,
            'storage_id' => null
        ]);

         event(new TyreCheckedOut(
                 $tyre->client->id,
                 $tyre->id
             ));
    }
}
