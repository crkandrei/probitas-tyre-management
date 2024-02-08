<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Tyre;

class DashboardController extends Controller
{
    public function getStats()
    {
        $totalTyres = Tyre::count();
        $totalClients = Client::count();

        return response()->json([
            'totalTyres' => $totalTyres,
            'totalClients' => $totalClients
        ]);
    }
}