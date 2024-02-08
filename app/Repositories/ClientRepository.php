<?php
namespace App\Repositories;

use App\Models\Client;

class ClientRepository
{
    protected const paginationLimit = 10;

    public function searchTyres($searchTerm)
    {
        return Client::where(function($query) use ($searchTerm) {
                $query->where('name', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('telephone', 'LIKE', "%{$searchTerm}%")
                    ->orWhereHas('tyres', function ($q) use ($searchTerm) {
                        $q->where('car_number', 'LIKE', "%{$searchTerm}%");
                    });
            })
            ->with('tyres')
            ->paginate(self::paginationLimit);
    }

    public function add($data)
    {
        return Client::create([
            'name' => $data['name'],
            'telephone' => $data['telephone']
        ]);
    }

    public function list()
    {
        return Client::all();
    }

    public function listForCheckIn()
    {
        return Client::where('created_at', '>=', now()->subDays(30))
            ->orWhereHas('tyres')
            ->get();
    }
}
