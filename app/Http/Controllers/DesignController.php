<?php

namespace App\Http\Controllers;

use App\Models\Design;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class DesignController extends Controller
{
    public function index(){
        return view('design');
    }

    public function getDesigns(Request $request){
        $designs = Design::all();

        if($request->ajax()){
            return DataTables::of($designs)
            ->addIndexColumn()

            ->addColumn('action', function ($row){
                return '<a href="javascript:void(0)" class="btn btn-info btn-sm editButton" data-id='. encrypt($row->id).' data-name="' . $row->name .'"  data-bs-toggle="modal" data-bs-target="#editDesignModal">Edit</a>
                        <a href="javascript:void(0)" class="btn btn-danger btn-sm deleteButton" data-id="'. encrypt($row->id) .'" data-name="'. $row->name .'">Delete</a>
                ';
            })
            ->make(true);
        }
    }

    public function store(Request $request){
        try{

            $request->validate([
                'design' => 'required'
            ]);

            Design::create([
                'name' => $request->design
            ]);

            return response()->json([
                'status' => 200,
                'message' => 'Design Stored Successfully'
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
                'design' => 'required'
            ]);

            Design::whereId(decrypt($request->id))->update([
                'name' => $request->design
            ]);

            return response()->json([
                'status' => 200,
                'message' => 'Design Updated Successfully'
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

            $design = Design::find(decrypt($request->id));
    
            if($design){
                $design->delete();
                return response()->json([
                    'status' => 200,
                    'message' => 'Design Deleted Successfully!',
                ], 200);
            }else{
                return response()->json([
                    'status' => 404,
                    'message' => 'Design Not Found!',
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
