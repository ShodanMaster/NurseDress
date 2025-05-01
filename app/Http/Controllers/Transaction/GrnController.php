<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Models\Barcode;
use App\Models\Grn;
use App\Models\GrnSub;
use App\Models\Item;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GrnController extends Controller
{
    public function index(){
        $grnNumber = Grn::nextNumber();
        $locations = Location::all();
        $items = Item::all();
        return view('transactions.grn', compact('grnNumber', 'locations', 'items'));
    }
    public function store(Request $request){
        // dd($request->all());

        DB::beginTransaction();

        if($request){
            $grnNumber = Grn::nextNumber();

            $grn = new Grn();
            $grn->grn_no = $grnNumber;
            $grn->invoice_no = $request->invoiceno;
            $grn->invoice_date = $request->invoicedate;
            $grn->location_id = $request->location_id;
            $grn->remarks = $request->remarks;
            $grn->quantity = $request->total_barcode;
            $grn->status = 0;
            $grn->employee_id = auth()->guard('employee')->id();

            $grn->save();

            $grnId = $grn->id;

            foreach($request->items as $item){
                $grnSub = new GrnSub();

                $grnSub->grn_id = $grnId;
                $grnSub->item_id = $item['item_id'];
                $grnSub->quantity = $item['quantity'];
                $grnSub->barcodes = $item['barcodes'];
                $grnSub->status = 0;
                $grnSub->employee_id = auth()->guard('employee')->id();

                $grnSub->save();

                $barcodes = $item['barcodes'];
                $total_price = (int)$item['quantity'] * (int)$item['amount'];
                while ($barcodes--){
                    $nextBarcode  = Barcode::nextNumber();

                    $barcodeObj = new Barcode();
                    $barcodeObj->barcode = $nextBarcode ;
                    $barcodeObj->grn_id = $grnId;
                    $barcodeObj->location_id = $request->location_id;
                    $barcodeObj->item_id = $item['item_id'];
                    $barcodeObj->price = $item['amount'];
                    $barcodeObj->total_price = $item['amount'];
                    $barcodeObj->quantity = 1;
                    $barcodeObj->status = '-1';
                    $barcodeObj->qc_status = '0';

                    $barcodeObj->save();

                }
            }
        }else{
            flash()->warning('Nothing To Save');
            return redirect()->back();
        }

        DB::commit();
        flash()->success("GRN Entry Successful: ".$grn->grn_no);
        return redirect()->back();
    }

    public function edit(){
        $grnNumbers = Grn::where('qc_status', '!=', 2)->get();
        $locations = Location::all();
        $items = Item::all();
        return view('transactions.grnedit', compact('grnNumbers', 'locations', 'items'));
    }

    public function fetchGrn(Request $request){
        // dd($request->all());
        $grn = Grn::whereId($request->grn_number)->first();

        if(!$grn){
            return response()->json([
                'status' => 404,
                'message' => 'GRN Not Found'
            ]);
        }

        $grn = Grn::with(['grnSubs.item'])
            ->whereId( $request->grn_number)
            ->first();

        $data = [
            'location_id' => $grn->location_id,
            'invoice_no' => $grn->invoice_no,
            'invoice_date' => $grn->invoice_date,
            'remarks' => $grn->remarks,
            'grn_subs' => $grn->grnSubs->map(function($sub) {
                return [
                    'item_id' => $sub->item_id,
                    'item_name' => $sub->item->title ?? 'Unknown',
                    'quantity' => $sub->quantity,
                ];
            }),
        ];

        return response()->json([
            'status' => 200,
            'message' => 'GRN Found',
            'data' => $data
        ])->setStatusCode(200, 'GRN Found');

    }

    public function update(Request $request){
        // dd($request->all());
        DB::beginTransaction();

        if($request){

            $grn = Grn::whereId($request->grnnumber)->first();

            $grn->invoice_no = $request->invoiceno;
            $grn->invoice_date = $request->invoicedate;
            $grn->location_id = $request->location_id;
            $grn->remarks = $request->remarks;

            $grn->status = 0;
            $grn->employee_id = auth()->guard('employee')->id();

            $grn->save();

            $grnId = $grn->id;

            $grn->barcodes()->delete();
            $grn->grnSubs()->delete();

            foreach($request->items as $item){
                $grnSub = new GrnSub();

                $grnSub->grn_id = $grnId;
                $grnSub->item_id = $item['item_id'];
                $grnSub->quantity = $item['quantity'];
                $grnSub->barcodes = $item['barcodes'];
                $grnSub->accepted_qty = 0;
                $grnSub->rejected_qty = 0;
                $grnSub->status = 0;
                $grnSub->employee_id = auth()->guard('employee')->id();

                $grnSub->save();

                $barcodes = $item['barcodes'];
                // $total_price = (int)$item['quantity'] * (int)$item['amount'];
                while ($barcodes--){
                    $nextBarcode  = Barcode::nextNumber();

                    $barcodeObj = new Barcode();
                    $barcodeObj->barcode = $nextBarcode ;
                    $barcodeObj->grn_id = $grnId;
                    $barcodeObj->location_id = $request->location_id;
                    $barcodeObj->item_id = $item['item_id'];
                    $barcodeObj->price = $item['amount'];
                    $barcodeObj->total_price = $item['amount'];
                    $barcodeObj->quantity = 1;
                    $barcodeObj->status = '-1';
                    $barcodeObj->qc_status = '0';

                    $barcodeObj->save();

                }
            }
        }else{
            flash()->warning('Nothing To Save');
            return redirect()->back();
        }

        DB::commit();
        flash()->success("GRN Update Successful: ".$grn->grn_no);
        return redirect()->back();
    }
}
