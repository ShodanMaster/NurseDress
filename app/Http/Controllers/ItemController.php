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
        return view('item');
    }

    public function getItems(Request $request){
        $items = Item::all();

        if($request->ajax()){
            return DataTables::of($items)
            ->addIndexColumn()

            ->addColumn('action', function ($row){
                return '<a href="javascript:void(0)" class="btn btn-info btn-sm editButton" data-id='. encrypt($row->id).' data-name="' . $row->name .'"  data-bs-toggle="modal" data-bs-target="#editItemModal">Edit</a>
                        <a href="javascript:void(0)" class="btn btn-danger btn-sm deleteButton" data-id="'. encrypt($row->id) .'" data-name="'. $row->name .'">Delete</a>
                ';
            })
            ->make(true);
        }
    }

    public function store(Request $request){
        dd($request->all());
        try{

            $request->validate([
                'item' => 'required'
            ]);

            Item::create([
                'name' => $request->item
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
            $request->validate([
                'item' => 'required'
            ]);

            Item::whereId(decrypt($request->id))->update([
                'name' => $request->item
            ]);

            return response()->json([
                'status' => 200,
                'message' => 'Item Updated Successfully'
            ], 200);

        }catch (Exception $e) {
            
            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                $message = 'Duplicate entry found. Please ensure the data is unique.';
            } else {
                $message = 'Something Went Wrong. Please try again later.';
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
