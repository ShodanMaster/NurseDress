<?php

namespace App\Imports;

use App\Models\Color;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ColorImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            if (!empty($row['name'])) {
                Color::firstOrCreate([
                    'name' => $row['name'],
                ]);
            }
        }
    }

    public function rules(): array
    {
        return [
            '*.name' => 'required|string|max:255',
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.name.required' => 'Color name is required in row :attribute.',
        ];
    }
}
