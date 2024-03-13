<?php

namespace App\Exports;

use App\Models\Client;
use App\Models\Tyre;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;


class TyreDataExport implements FromCollection, WithMapping, WithHeadings, ShouldAutoSize, WithStyles
{
    public function collection(): Collection
    {
        return Tyre::whereHas('client', function ($query) {
            $query->whereNull('deleted_at');
        })->with('client')->with('storage')->get();
    }

    public function map($row): array
    {
        $clientName = optional($row->client)->name ?? 'Client Sters';
        $clientTelephone = optional($row->client)->telephone ?? 'Client Sters';
        $storageLocation = $this->generateStorageString($row);
        return [
            $clientName,
            $clientTelephone,
            $row->car_number,
            $row->model,
            $row->size,
            $row->quantity,
            $row->has_rim ? 'Da' : 'Nu',
            $row->observations,
            $storageLocation
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
            'Locatie Depozitare'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Assuming $numRows is dynamically set based on your data
        $numRows = $this->collection()->count() + 1; // +1 for the header row

        // Dynamically setting the last cell of the range based on the number of rows
        $lastCell = 'I' . $numRows;

        $sheet->getStyle('A1:' . $lastCell)->applyFromArray([
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
                'inside' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ]);

        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => ['argb' => 'FFFF00'],
                ],
            ],
        ];
    }


    private function generateStorageString(mixed $row)
    {
        $storageLocation = '';

        if ($row->storage) {
            if ($row->storage->row) {
                $storageLocation .= 'R:'. $row->storage->row;
            }

            if ($row->storage->column) {
                $storageLocation .= ' C:' . $row->storage->column;
            }

            if ($row->storage->shelf) {
                $storageLocation .= ' RF:' . $row->storage->shelf;
            }

            if ($row->storage->observations) {
                $storageLocation .= ' (' . $row->storage->observations . ')';
            }

            return $storageLocation;
        }

        return 'Nedepozitat';
    }



}
