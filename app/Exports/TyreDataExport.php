<?php

namespace App\Exports;

use App\Models\Client;
use App\Models\Tyre;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;


class TyreDataExport implements FromCollection, WithMapping, WithHeadings
{
    public function collection(): Collection
    {
        return Tyre::whereHas('client', function ($query) {
            $query->whereNull('deleted_at');
        })->with('client')->get();
    }

    public function map($row): array
    {
        $clientName = optional($row->client)->name ?? 'Client Sters';
        $clientTelephone = optional($row->client)->telephone ?? 'Client Sters';
        return [
            $clientName,
            $clientTelephone,
            $row->car_number,
            $row->model,
            $row->size,
            $row->quantity,
            $row->has_rim ? 'Da' : 'Nu',
            $row->observations,
        ];
    }

    public function headings(): array
    {
        // Define column headings
        return [
            'Nume',
            'Telefon',
            'Numar masina',
            'Model',
            'Dimensiune',
            'Cantitate',
            'Janta',
            'Observatii',
        ];
    }
}
