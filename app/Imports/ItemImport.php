<?php
namespace App\Imports;

use App\Models\Color;
use App\Models\Design;
use App\Models\Item;
use App\Models\Size;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ItemImport implements ToCollection, WithHeadingRow, WithValidation
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        // Loop through each row and create the Item if valid
        foreach ($rows as $row) {
            if (!empty($row['title']) && !empty($row['sex']) && !empty($row['color']) &&
                !empty($row['size']) && !empty($row['design']) && !empty($row['amount'])) {

                $size = Size::firstOrCreate(['name' => trim($row['size'])]);
                $color = Color::firstOrCreate(['name' => trim($row['color'])]);
                $design = Design::firstOrCreate(['name' => trim($row['design'])]);

                Item::create([
                    'size_id' => $size->id,
                    'color_id' => $color->id,
                    'design_id' => $design->id,
                    'title' => trim($row['title']),
                    'sex' => trim($row['sex']),
                    'amount' => $row['amount'],
                    'box_quantity' => $row['box_quantity'],
                ]);
            }
        }
    }

    // Validation rules for each row
    public function rules(): array
    {
        return [
            '*.title' => 'required|string|max:255',
            '*.sex' => 'required|in:Male,Female',
            '*.color' => 'required|string|max:50',
            '*.size' => 'required|string|max:50',
            '*.design' => 'required|string|max:50',
            '*.amount' => 'required|numeric|min:0',
            '*.box_quantity' => 'nullable|numeric|min:0|max:100',
        ];
    }

    // Custom validation messages for each field
    public function customValidationMessages()
    {
        return [
            '*.title.required' => 'Title is required in row :attribute.',
            '*.sex.required' => 'Sex is required in row :attribute.',
            '*.sex.in' => 'Sex must be either Male or Female in row :attribute.',
            '*.amount.numeric' => 'Amount must be numeric in row :attribute.',
            // Add more custom messages as needed
        ];
    }

}
