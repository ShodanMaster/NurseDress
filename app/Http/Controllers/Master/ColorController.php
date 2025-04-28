<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Color;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ColorController extends Controller
{
    public function index(){
        return view('master.color');
    }

    public function getColors(Request $request){
        $colors = Color::all();

        if($request->ajax()){
            return DataTables::of($colors)
            ->addIndexColumn()

            ->addColumn('action', function ($row){
                return '<a href="javascript:void(0)" class="btn btn-info btn-sm editButton" data-id='. encrypt($row->id).' data-name="' . $row->name .'"  data-bs-toggle="modal" data-bs-target="#editColorModal">Edit</a>
                        <a href="javascript:void(0)" class="btn btn-danger btn-sm deleteButton" data-id="'. encrypt($row->id) .'" data-name="'. $row->name .'">Delete</a>
                ';
            })
            ->make(true);
        }
    }

    public function store(Request $request){
        try{

            $request->validate([
                'color' => 'required'
            ]);

            Color::create([
                'name' => $request->color
            ]);

            return response()->json([
                'status' => 200,
                'message' => 'Color Stored Successfully'
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
                'color' => 'required'
            ]);

            Color::whereId(decrypt($request->id))->update([
                'name' => $request->color
            ]);

            return response()->json([
                'status' => 200,
                'message' => 'Color Updated Successfully'
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

            $color = Color::find(decrypt($request->id));

            if($color){
                $color->delete();
                return response()->json([
                    'status' => 200,
                    'message' => 'Color Deleted Successfully!',
                ], 200);
            }else{
                return response()->json([
                    'status' => 404,
                    'message' => 'Color Not Found!',
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
