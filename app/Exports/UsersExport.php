<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UsersExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return User::all();
    }
    
    /**
    * Map each row of data
    */
    public function map($user): array
    {
        static $index = 1;
        return [
            $index++,
            $user->username,
            $user->type,
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
            'Type'
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
