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
        $grnNumbers = Grn::all();
        return view('transactions.qc', compact('grnNumbers'));
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

            $grn = Grn::where('id', $validated['grnnumber'])->first();
            if(!$grn){
                return redirect()->back()->with('error', 'Invalid GRN number!');
            }

            $grn->qc_status = '1';
            $grn->save();

            foreach($validated['items'] as $item){
                $grn->grnSubs()->updateOrCreate(
                    ['item_id' => $item['item_id']],
                    [
                        // 'quantity' => $item['quantity'],
                        'scanned_qty' => $item['accepted'],
                        'rejected_qty' => $item['rejected'],
                    ]
                );
            }

            return redirect()->back()->with('success', 'QC updated successfully!');


        }catch(Exception $e){
            dd($e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong! Please try again.');
        }
    }
}
