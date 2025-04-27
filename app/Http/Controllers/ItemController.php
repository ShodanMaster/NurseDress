<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Size;
use App\Models\Color;
use App\Models\Design;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ItemController extends Controller
{
    public function index(){
        $sizes = Size::all();
        $colors = Color::all();
        $designs = Design::all();
        return view('item', compact('sizes','colors', 'designs'));
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
                ->addColumn('design', function ($row) {
                    return $row->design->name;
                })
                ->addColumn('action', function ($row) {
                    return '<a href="javascript:void(0)" class="btn btn-info btn-sm editButton" data-id="' . encrypt($row->id) . '" data-item="' . $row->title . '" data-size="' . $row->size->id . '" data-color="' . $row->color->id . '" data-design="' . $row->design->id . '" data-sex="' . $row->sex . '" data-bs-toggle="modal" data-bs-target="#editItemModal">Edit</a>
                            <a href="javascript:void(0)" class="btn btn-danger btn-sm deleteButton" data-id="' . encrypt($row->id) . '" data-title="' . $row->title . '">Delete</a>';
                })
                ->make(true);
        }
    }


    public function store(Request $request){
        // dd($request->all());
        try{

            $validated = $request->validate([
                'size' => 'required|integer|exists:sizes,id',
                'color' => 'required|integer|exists:colors,id',
                'design' => 'required|integer|exists:designs,id',
                'sex' => 'required|in:male,female',
                'item' => 'required|string|max:250',
            ]);

            Item::create([
                'size_id' => $validated['size'],
                'color_id' => $validated['color'],
                'design_id' => $validated['design'],
                'sex' => $validated['sex'],
                'title' => $validated['item'],
            ]);

            return response()->json([
                'status' => 200,
                'message' => 'Item Stored Successfully'
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

    public function update(Request $request){
        // dd($request->all());
        try{
            $validated = $request->validate([
                'size' => 'required|integer|exists:sizes,id',
                'color' => 'required|integer|exists:colors,id',
                'design' => 'required|integer|exists:designs,id',
                'sex' => 'required|in:male,female',
                'item' => 'required|string|max:250',
            ]);

            Item::whereId(decrypt($request->id))->update([
                'size_id' => $validated['size'],
                'color_id' => $validated['color'],
                'design_id' => $validated['design'],
                'sex' => $validated['sex'],
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
}
