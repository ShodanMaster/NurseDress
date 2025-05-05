<?php

namespace App\Http\Controllers\Master;

use App\Exports\BinsExport;
use App\Http\Controllers\Controller;

use App\Models\Bin;
use App\Models\Location;
use Exception;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class BinController extends Controller

{
    public function index(){
        $locations = Location::all();
        return view('master.bin', compact('locations'));
    }

    public function getBins(Request $request){
        $bins = Bin::all();

        if($request->ajax()){
            return DataTables::of($bins)
            ->addIndexColumn()
            ->addColumn('location', function ($row) {
                return $row->location->name;
            })
            ->addColumn('action', function ($row){
                return '<a href="javascript:void(0)" class="btn btn-info btn-sm editButton" data-id='. encrypt($row->id).' data-location_id='. $row->location->id.' data-name="' . $row->name .'"  data-bs-toggle="modal" data-bs-target="#editBinModal">Edit</a>
                        <a href="javascript:void(0)" class="btn btn-danger btn-sm deleteButton" data-id="'. encrypt($row->id) .'" data-name="'. $row->name .'">Delete</a>
                ';
            })
            ->make(true);
        }
    }

    public function store(Request $request){
        // dd($request->all());
        try{

            $request->validate([
                'location_id' => 'required|exists:locations,id',
                'bin' => 'required|string|max:255',
            ]);

            Bin::create([

                'location_id' => $request->location_id,
                'name' => $request->bin,
            ]);

            return response()->json([
                'status' => 200,
                'message' => 'Bin Stored Successfully'
            ], 200);

        }catch (Exception $e) {

            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                $message = 'Duplicate entry found. Please ensure the data is unique.';
            } else {
                $message = 'Something Went Wrong. Please try again later. ' . $e->getMessage();
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
                'location_id' => 'required|exists:locations,id',
                'bin' => 'required|string|max:255',
            ]);

            Bin::whereId(decrypt($request->id))->update([
                'location_id' => $request->location_id,
                'name' => $request->bin,
            ]);

            return response()->json([
                'status' => 200,
                'message' => 'Bin Updated Successfully'
            ], 200);

        }catch (Exception $e) {

            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                $message = 'Duplicate entry found. Please ensure the data is unique.';
            } else {
                $message = 'Something Went Wrong. Please try again later.' . $e->getMessage();
            }

            return response()->json([
                'message' => $message,
            ], 500);
        }
    }

    public function delete(Request $request){
        try{

            $bin = Bin::find(decrypt($request->id));

            if($bin){
                $bin->delete();
                return response()->json([
                    'status' => 200,
                    'message' => 'Bin Deleted Successfully!',
                ], 200);
            }else{
                return response()->json([
                    'status' => 404,
                    'message' => 'Bin Not Found!',
                ], 404);
            }
        }catch(Exception $e){
            return response()->json([
                'status' => 500,
                'message' => 'Something Went Wrong '. $e->getMessage(),
            ]);
        }
    }

    public function binExcelExport(){
        return Excel::download(new BinsExport, 'bins.xlsx');
    }
}
