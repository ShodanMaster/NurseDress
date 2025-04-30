<?php
namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Models\Grn;
use Exception;
use Illuminate\Http\Request;

class QcController extends Controller
{
    public function index()
    {
        $grnNumbers = Grn::where('qc_status', '!=', 1)->get();
        return view('transactions.qc', compact('grnNumbers'));
    }

    public function fetchItem(Request $request){
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

                $quantity= $sub->quantity;
                $pending = $sub->pending;

                if (!$pending == null){
                    $quantity = $quantity - $pending;
                }

                return [
                    'item_id' => $sub->item_id,
                    'item_name' => $sub->item->title ?? 'Unknown',
                    'quantity' => $quantity - $pending,
                ];
            }),
        ];

        return response()->json([
            'status' => 200,
            'message' => 'GRN Found',
            'data' => $data
        ])->setStatusCode(200, 'GRN Found');

    }

    public function store(Request $request){
        // dd($request->all());

        try{
            $validated = $request->validate([
                'grnnumber' => 'required',
                'items' => 'required|array',
                'items.*.item_id' => 'required|exists:items,id',
                'items.*.quantity' => 'required|numeric|min:0',
                'items.*.accepted' => 'required|numeric|min:0',
                'items.*.rejected' => 'required|numeric|min:0',

            ]);

            // $grn = Grn::where('id', $validated['grnnumber'])->first();

            $grn = Grn::find($validated['grnnumber']);
            if(!$grn){
                return redirect()->back()->with('error', 'Invalid GRN number!');
            }

            foreach ($validated['items'] as $item) {
                $sub = $grn->grnSubs()->firstOrNew(['item_id' => $item['item_id']]);

                // $sub->quantity = $item['quantity'];

                $sub->accepted_qty = ($sub->accepted_qty ?? 0) + $item['accepted'];
                $sub->rejected_qty = ($sub->rejected_qty ?? 0) + $item['rejected'];

                $pending = $sub->quantity - ($sub->accepted_qty + $sub->rejected_qty);
                $sub->pending_qty = max(0, $pending);

                $sub->save();
            }


            $totalPending = $grn->grnSubs()->sum('pending_qty');

            if ($totalPending == 0) {
                $grn->qc_status = 1;
            } else {
                $grn->qc_status = 2;
            }

            $grn->save();

            return redirect()->back()->with('success', 'QC updated successfully!');


        }catch(Exception $e){
            dd($e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong! Please try again.');
        }
    }
}
