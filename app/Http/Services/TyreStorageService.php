<?php

namespace App\Http\Services;


use App\Events\TyreDeposited;
use App\Models\Storage;
use App\Models\Tyre;


class TyreStorageService
{
    const DEFAULT_DEPOSIT_ID = 1;

    /**
     * @throws \App\Exceptions\StorageLocationAlreadyAssignedException
     */
    public function assignStorage($validatedData)
    {
        // Retrieve the tyre
        $tyre = Tyre::with('client')->findOrFail($validatedData['tyreId']);

        $storage = Storage::create([
            'row' => $validatedData['row'],
            'column' => $validatedData['column'],
            'shelf' => $validatedData['shelf'],
            'observations' => $validatedData['observations'] ?? null,
            'deposit_id' => self::DEFAULT_DEPOSIT_ID,
        ]);

        // Update tyre's status and storage
        $tyre->update([
            'status' => 1,
            'storage_id' => $storage->id
        ]);

        event(new TyreDeposited(
            $tyre->client->id,
            $tyre->id,$storage->row,
            $storage->column,
            $storage->shelf)
        );


        $storage->update([
            'observations' => $validatedData['observations'] ?? null,
        ]);

        return $tyre;
    }

    public function updateStorage($validated, $storageId): void
    {
        // Retrieve the tyre linked to the current storage
        $tyre = Tyre::where('storage_id', $storageId)->firstOrFail();

        // Create a new storage with the validated data
        $newStorage = Storage::create([
            'row' => $validated['row'],
            'column' => $validated['column'],
            'shelf' => $validated['shelf'],
            'observations' => $validated['observations'],
            'deposit_id' => self::DEFAULT_DEPOSIT_ID
        ]);

        // Assign the new storage to the tyre
        $tyre->update(['storage_id' => $newStorage->id]);

        event(new TyreDeposited(
            $tyre->client->id,
            $tyre->id,
            $existingStorage->row ?? $newStorage->row,
            $existingStorage->column ?? $newStorage->column,
            $existingStorage->shelf ?? $newStorage->shelf
            ));
    }

}
