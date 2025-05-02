<?php
namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;

use App\Models\Barcode;
use App\Models\Bin;
use App\Models\Grn;
use Illuminate\Http\Request;

class StorageController extends Controller
{


    public function index(){
        $grnNumbers = Grn::where('qc_status', 1)->get();
        return view('transactions.storage',compact('grnNumbers'));
    }

    public function fetchBin(Request $request){
        // dd($request->all());
        $bin = Bin::where('name', $request->bin)->first();

        if(!$bin){
            return response()->json([
                'status' => 404,
                'message' => 'Bin not found',
            ]);
        }

        return response()->json([
            'status' => 200,
            'message' => 'Bin found',
        ]);
    }

    public function store(Request $request){
        dd($request->all());
        try {
            $request->validate([
                'barcode' => 'required|exists:barcodes,barcode',
                'grn_number' => 'required|exists:grns,id',
                'bin' => 'required|exists:bins,name',
            ]);

            $barcode = Barcode::where('barcode', $request->barcode)->update([
                'qc_status' => 1,
                'status' => 2,
            ]);


            if ($request->ajax()) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Barcode Storaged successfully.',
                ]);
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
