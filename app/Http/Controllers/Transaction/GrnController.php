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
            $grn->status = 0;

            $grn->save();

            $grnId = $grn->id;

            foreach($request->items as $item){
                $grnSub = new GrnSub();

                $grnSub->grn_id = $grnId;
                $grnSub->item_id = $item['item_id'];
                $grnSub->quantity = $item['quantity'];
                $grnSub->barcodes = $item['barcodes'];
                $grnSub->scanned_qty = 0;
                $grnSub->rejected_qty = 0;
                $grnSub->status = 0;

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
                    $barcodeObj->status = '-1';
                    $barcodeObj->qc_status = '0';

                    $barcodeObj->save();
                    
                }
            }
        }else{
            return redirect()->back()->with('error', 'Nothing To Save');
        }

        DB::commit();
        return redirect()->back()->with('success', "GRN Entry Successful: ".$grn->grn_no);
    }
}
