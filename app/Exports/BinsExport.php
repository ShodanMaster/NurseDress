<?php

namespace App\Exports;

use App\Models\Bin;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BinsExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Bin::all();
    }

    /**
    * Map each row of data
    */
    public function map($bin): array
    {
        static $index = 1;
        return [
            $index++,
            $bin->name,
            $bin->location->name,
        ];
    }

    /**
        * Define custom headings
        */
    public function headings(): array
    {
        return [
            'S.No',
            'Name',
            'Location',
        ];
    }

    /**
        * Apply styles to the sheet
        */

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
