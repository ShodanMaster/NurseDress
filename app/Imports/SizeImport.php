<?php

namespace App\Imports;

use App\Models\Size;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;

class SizeImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $name = trim(strtolower($row['name']));
            if (!empty($row['name'])) {
                Size::firstOrCreate([
                    'name' => $name,
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
            '*.name.required' => 'Size name is required in row :attribute.',
        ];
    }

}
