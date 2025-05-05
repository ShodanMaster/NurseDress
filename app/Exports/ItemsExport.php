<?php

namespace App\Exports;

use App\Models\Item;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ItemsExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Item::all();
    }

    /**
    * Map each row of data
    */
    public function map($item): array
    {
        static $index = 1;
        return [
            $index++,
            $item->title,
            $item->sex,
            $item->size->name,
            $item->color->name,
            $item->design->name,
            $item->amount,
        ];
    }

    /**
        * Define custom headings
        */
    public function headings(): array
    {
        return [
            'S.No',
            'Title',
            'Sex',
            'Size',
            'Color',
            'Design',
            'Amount',
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
