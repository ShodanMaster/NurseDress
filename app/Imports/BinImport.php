<?php

namespace App\Imports;

use App\Models\Bin;
use App\Models\Location;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BinImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // dd($row);
            if (!empty($row['location']) && !empty($row['bin']) ){
                $location = Location::firstOrCreate([
                    'name' => $row['location']
                ]);

                Bin::firstOrCreate([
                    'location_id' => $location->id,
                    'name' => $row['bin'],
                ]);
            }
        }
    }

    public function rules(): array
    {
        return [
            '*.location' => 'required|string|max:255',
            '*.bin' => 'required|string|max:255',
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.location.required' => 'Location is required in row :attribute.',
            '*.bin.required' => 'bin is required in row :attribute.',
        ];
    }
}
