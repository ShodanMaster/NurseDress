<?php

namespace App\Exports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EmployeesExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Employee::all();
    }
    
    /**
    * Map each row of data
    */
    public function map($employee): array
    {
        static $index = 1;
        return [
            $index++,
            $employee->name,
            $employee->phone,
            $employee->vehicle_number,
            $employee->company,
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
            'Phone',
            'Vehicle Number',
            'Company',
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
