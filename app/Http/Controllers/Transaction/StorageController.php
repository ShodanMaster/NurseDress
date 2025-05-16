<?php
namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Http\Requests\Transaction\StorageScanRequest;
use App\Models\Barcode;
use App\Models\Bin;
use App\Models\Grn;
use App\Models\GrnSub;
use App\Models\Qc;
use App\Models\StorageScan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

    public function store(StorageScanRequest $request){

        try {
            $validated = $request->validated();

            if ($request->ajax()) {
                DB::beginTransaction();

                $barcode = Barcode::where('barcode', $request->barcode)->first();

                if (!$barcode) {
                    return response()->json([
                        'status' => 404,
                        'message' => 'Barcode not found.'
                    ]);
                }

                $grnSub = GrnSub::where('grn_id', $barcode->grn_id)
                                ->where('item_id', $barcode->item_id)
                                ->first();

                $qc = Qc::where('grn_id', $barcode->grn_id)
                    ->where('item_id', $barcode->item_id)
                    ->first();

                if (!$grnSub || !$qc) {
                    return response()->json([
                        'status' => 404,
                        'message' => 'GRN or QC data not found.'
                    ]);
                }

                if ($grnSub->accepted_qty >= $qc->accepted_qty) {
                    return response()->json([
                        'status' => 422,
                        'message' => 'The quantity exceeds the allowed limit.'
                    ]);
                }

                $bin = Bin::where('name', $validated['bin'])->first();

                if (!$bin) {
                    return response()->json([
                        'status' => 404,
                        'message' => 'Bin not found.'
                    ]);
                }

                $storageScan = new StorageScan();
                $storageScan->barcode = $validated['barcode'];
                $storageScan->grn_id = $validated['grn_number'];
                $storageScan->bin_id = $bin->id;
                $storageScan->item_id = $barcode->item_id;
                $storageScan->scanned_quantity = 1;
                $storageScan->user_id = Auth::id();

                $grnSub->accepted_qty += 1;

                if($grnSub->accepted_qty == $qc->accepted_qty && $grnSub->rejected_qty == $qc->rejected_qty){
                    Grn::where('id', $barcode->grn_id)->update(['status',1]);
                }

                $barcode->status = '1';
                $barcode->qc_status = 1;
                $barcode->save();

                $grnSub->save();
                $storageScan->save();

                DB::commit();

                return response()->json([
                    'status' => 200,
                    'message' => 'Storage Scan Successful',
                ]);
            }

        } catch (\Exception $e) {
            DB::rollBack();
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
