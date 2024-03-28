<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Tyre;
use Illuminate\Http\JsonResponse;


class DashboardController extends Controller
{
    public function getStats(): JsonResponse
    {
        $totalTyres =  Tyre::whereHas('client', function ($query) {
            $query->whereNull('deleted_at');
        })->where('status', '!=', 2)->count();

        $totalClients = Client::count();

        return response()->json([
            'totalTyres' => $totalTyres,
            'totalClients' => $totalClients
        ]);
    }
}
