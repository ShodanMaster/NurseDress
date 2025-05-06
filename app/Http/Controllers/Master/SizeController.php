<?php

namespace App\Http\Controllers\Master;

use App\Exports\SizesExport;
use App\Http\Controllers\Controller;
use App\Imports\SizeImport;
use App\Models\Size;
use Exception;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class SizeController extends Controller
{
    public function index(){
        return view('master.size');
    }

    public function getSizes(Request $request){
        $sizes = Size::all();

        if($request->ajax()){
            return DataTables::of($sizes)
            ->addIndexColumn()

            ->addColumn('action', function ($row){
                return '<a href="javascript:void(0)" class="btn btn-info btn-sm editButton" data-id='. encrypt($row->id).' data-name="' . $row->name .'"  data-bs-toggle="modal" data-bs-target="#editSizeModal">Edit</a>
                        <a href="javascript:void(0)" class="btn btn-danger btn-sm deleteButton" data-id="'. encrypt($row->id) .'" data-name="'. $row->name .'">Delete</a>
                ';
            })
            ->make(true);
        }
    }

    public function store(Request $request){
        // dd($request->all());
        try{

            if($request->file()){
                $request->validate([
                    'excelSize' => 'required|mimes:xlsx,xls,csv|max:2048',
                ]);

                // dd($request->file('excelSize')->getClientOriginalExtension());

                Excel::import(new SizeImport, $request->file('excelSize'));
            }
            elseif($request->size){
                $request->validate([
                    'size' => 'required'
                ]);

                Size::create([
                    'name' => $request->size
                ]);
            }


            return response()->json([
                'status' => 200,
                'message' => 'Size Stored Successfully'
            ], 200);

        }catch (Exception $e) {

            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                $message = 'Duplicate entry found. Please ensure the data is unique.';
            } else {
                $message = 'Something Went Wrong. Please try again later. '.$e->getMessage();
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
                'size' => 'required'
            ]);

            Size::whereId(decrypt($request->id))->update([
                'name' => $request->size
            ]);

            return response()->json([
                'status' => 200,
                'message' => 'Size Updated Successfully'
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

            $size = Size::find(decrypt($request->id));

            if($size){
                $size->delete();
                return response()->json([
                    'status' => 200,
                    'message' => 'Size Deleted Successfully!',
                ], 200);
            }else{
                return response()->json([
                    'status' => 404,
                    'message' => 'size Not Found!',
                ], 404);
            }
        }catch(Exception $e){
            return response()->json([
                'status' => 500,
                'message' => 'Something Went Wrong '. $e->getMessage(),
            ]);
        }
    }

    public function sizeExcelExport(){
        return Excel::download(new SizesExport, 'sizes.xlsx');
    }
}
