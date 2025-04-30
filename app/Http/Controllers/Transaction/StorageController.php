<?php
namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;

use App\Models\Barcode;
use Illuminate\Http\Request;

class StorageController extends Controller
{
    public function rejection(){
        return view('transactions.rejection');
    }

    public function store(Request $request){
        // dd($request->all());
        try {
            $request->validate([
                'barcode' => 'required|exists:barcodes,barcode',
            ]);

            $barcode = Barcode::where('barcode', $request->barcode)->update([
                'qc_status' => 2
            ]);


            if ($request->ajax()) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Barcode rejected successfully.',
                ]);
            } else {
                return redirect()->back()->with('success', 'Barcode rejected successfully.');
            }

        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'status' => 500,
                    'message' => 'Something went wrong.',
                    'error' => $e->getMessage(),
                ]);
            } else {
                return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
            }
        }
    }

}
