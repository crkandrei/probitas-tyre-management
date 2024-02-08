<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientAddRequest;
use App\Models\Client;
use App\Repositories\ClientRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;


class ClientController extends Controller
{
    protected ClientRepository $clientRepository;

    public function __construct(ClientRepository $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }

    public function index(Request $request)
    {
        $tyres = $this->clientRepository->searchTyres($request->search);
        return response()->json($tyres);
    }

    public function update(ClientAddRequest $request, Client $client): JsonResponse
    {
        $client->update($request->validated());

        return response()->json([
            'message' => 'Client updated successfully',
            'client' => $client
        ]);
    }

    public function history(Client $client)
    {
        $history = $client->history()->with(['client', 'tyre'])->get();

        $grouped = $history->groupBy(function ($item) {
            return $item->tyre->car_number; // Group by tyre name
        });

        $sortedGrouped = $grouped->map(function ($group) {
            return $group->sortBy('action_date'); // Sort each group by action_date
        });

        return Response::json($sortedGrouped);
    }

    public function add(ClientAddRequest $request)
    {
        $client = $this->clientRepository->add($request->all());

        return response()->json([
            'message' => 'Client adaugat cu succes!',
            'client' => $client
        ]);
    }

    public function list()
    {
        $clients = $this->clientRepository->listForCheckIn();
        return response()->json($clients);
    }
}