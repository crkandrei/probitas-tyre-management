<?php

namespace App\Http\Controllers;

use App\Exceptions\StorageLocationAlreadyAssignedException;
use App\Http\Requests\TyreStorageRequest;
use App\Http\Requests\UpdateTyreStorageRequest;
use App\Http\Services\TyreStorageService;
use App\Models\Storage;


class StorageController extends Controller
{
    protected TyreStorageService $tyreStorageService;

    public function __construct(TyreStorageService $tyreStorageService)
    {
        $this->tyreStorageService = $tyreStorageService;
    }

    public function store(TyreStorageRequest $request)
    {
        $validated = $request->validated();
        try{

            $tyre = $this->tyreStorageService->assignStorage($validated);

        } catch (StorageLocationAlreadyAssignedException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }

        return response()->json([
            'message' => 'Locatia din depozit a fost salvata cu succes!',
            'tyre' => $tyre,
        ]);
    }

    public function updateStorage(UpdateTyreStorageRequest $request, $storageId)
    {
        // Validate the request data
        $validated = $request->validated();

        try{
           $this->tyreStorageService->updateStorage($validated, $storageId);

        } catch (StorageLocationAlreadyAssignedException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }

        // Return a success response
        return response()->json([
            'message' => 'Locatia s-a updatat cu succes!'
        ]);
    }
}
