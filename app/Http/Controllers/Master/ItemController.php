<?php

namespace App\Http\Controllers\Master;

use App\Exports\ItemsExport;
use App\Http\Controllers\Controller;
use App\Imports\ItemImport;
use App\Models\Item;
use App\Models\Size;
use App\Models\Color;
use App\Models\Design;
use Exception;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class ItemController extends Controller
{
    public function index(){
        $sizes = Size::all();
        $colors = Color::all();
        $designs = Design::all();
        return view('master.item', compact('sizes','colors', 'designs'));
    }

    public function getItems(Request $request){

        $items = Item::with('size', 'color', 'design')->get();

        if ($request->ajax()) {
            return DataTables::of($items)
                ->addIndexColumn()
                ->addColumn('sex', function ($row) {
                    return $row->sex;
                })
                ->addColumn('size', function ($row) {
                    return $row->size->name;
                })
                ->addColumn('color', function ($row) {
                    return $row->color->name;
                })
                ->addColumn('amount', function ($row) {
                    return $row->amount;
                })
                ->addColumn('design', function ($row) {
                    return $row->design->name;
                })
                ->addColumn('box quantity', function ($row) {
                    return $row->box_quantity;
                })
                ->addColumn('action', function ($row) {
                    return '<a href="javascript:void(0)" class="btn btn-info btn-sm editButton" data-id="' . encrypt($row->id) . '" data-item="' . $row->title . '" data-amount="' . $row->amount . '" data-size="' . $row->size->id . '" data-color="' . $row->color->id . '" data-design="' . $row->design->id . '" data-sex="' . $row->sex . '" data-box_quantity="' . $row->box_quantity . '" data-bs-toggle="modal" data-bs-target="#editItemModal">Edit</a>
                            <a href="javascript:void(0)" class="btn btn-danger btn-sm deleteButton" data-id="' . encrypt($row->id) . '" data-title="' . $row->title . '">Delete</a>';
                })
                ->make(true);
        }
    }


    public function store(Request $request){
        // dd($request->all());
        try{
            if($request->file()){
                $request->validate([
                    'excelItem' => 'required|mimes:xlsx,xls,csv|max:2048',
                ]);

                // dd($request->file('excelSize')->getClientOriginalExtension());

                Excel::import(new ItemImport, $request->file('excelItem'));
            }
            elseif($request->size){

                $validated = $request->validate([
                    'size' => 'required|integer|exists:sizes,id',
                    'color' => 'required|integer|exists:colors,id',
                    'design' => 'required|integer|exists:designs,id',
                    'sex' => 'required|in:male,female',
                    'amount' => 'required|integer',
                    'box_quantity' => 'required|integer|max:100',
                    'item' => 'required|string|max:250',
                ]);

                Item::create([
                    'size_id' => $validated['size'],
                    'color_id' => $validated['color'],
                    'design_id' => $validated['design'],
                    'amount' => $validated['amount'],
                    'sex' => $validated['sex'],
                    'box_quantity' => $validated['box_quantity'],
                    'title' => $validated['item'],
                ]);
            }
            return response()->json([
                'status' => 200,
                'message' => 'Item Stored Successfully'
            ], 200);

        }catch (Exception $e) {

            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                $message = 'Duplicate entry found. Please ensure the data is unique.';
            } else {
                $message = 'Something Went Wrong. Please try again later. '. $e->getMessage();
            }

            return response()->json([
                'message' => $message,
            ], 500);
        }

    }

    public function update(Request $request){
        // dd($request->all());
        try{
            $validated = $request->validate([
                'size' => 'required|integer|exists:sizes,id',
                'color' => 'required|integer|exists:colors,id',
                'design' => 'required|integer|exists:designs,id',
                'sex' => 'required|in:male,female',
                'amount' => 'required|integer',
                'box_quantity' => 'required|integer|max:100',
                'item' => 'required|string|max:250',
            ]);

            Item::whereId(decrypt($request->id))->update([
                'size_id' => $validated['size'],
                'color_id' => $validated['color'],
                'design_id' => $validated['design'],
                'amount' => $validated['amount'],
                'sex' => $validated['sex'],
                'box_quantity' => $validated['box_quantity'],
                'title' => $validated['item'],
            ]);

            return response()->json([
                'status' => 200,
                'message' => 'Item Updated Successfully'
            ], 200);

        }catch (Exception $e) {

            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                $message = 'Duplicate entry found. Please ensure the data is unique.';
            } else {
                $message = 'Something Went Wrong. Please try again later.'. $e->getMessage();
            }

            return response()->json([
                'message' => $message,
            ], 500);
        }
    }

    public function delete(Request $request){
        try{

            $item = Item::find(decrypt($request->id));

            if($item){
                $item->delete();
                return response()->json([
                    'status' => 200,
                    'message' => 'Item Deleted Successfully!',
                ], 200);
            }else{
                return response()->json([
                    'status' => 404,
                    'message' => 'Item Not Found!',
                ], 404);
            }
        }catch(Exception $e){
            return response()->json([
                'status' => 500,
                'message' => 'Something Went Wrong '. $e->getMessage(),
            ]);
        }
    }

    public function itemExcelExport(){
        return Excel::download(new ItemsExport, 'items.xlsx');
    }
}
