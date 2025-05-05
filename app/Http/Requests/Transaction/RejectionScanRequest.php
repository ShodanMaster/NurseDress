<?php

namespace App\Http\Requests\Transaction;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class RejectionScanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'barcode' => [
            'required',
                Rule::exists('barcodes', 'barcode'),
                function ($attribute, $value, $fail) {
                    $barcode = DB::table('barcodes')->where('barcode', $value)->first();

                    $grn = $this->input('grn_number');
                    if($barcode->grn_id != $grn){
                        $fail("Barcode '{$barcode->barcode}' does not belong to this GRN Number.");
                    }

                    if ($barcode && $barcode->status == '1') {
                        $fail("Barcode '{$barcode->barcode}' Already scanned.");
                    }
                    elseif($barcode->status == 2){
                        $fail("Barcode '{$barcode->barcode}' Already Dispatched.");
                    }
                    elseif($barcode->status == 8){
                        $fail("Barcode '{$barcode->barcode}' Stocked Out.");
                    }

                },
            ],
            'grn_number' => 'exists:grns,id',
            'bin'        => 'exists:bins,name',
        ];
    }

    public function messages(): array
    {
        return [
            'barcode.required'   => 'Please scan a barcode.',
            'barcode.exists'     => 'This barcode is does not exist.',
            'grn_number.exists'  => 'Invalid GRN number.',
            'bin.exists'         => 'Invalid bin name.',
        ];
    }
}
