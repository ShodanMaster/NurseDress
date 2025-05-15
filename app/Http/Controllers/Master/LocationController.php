<?php

namespace App\Http\Controllers\Master;

use App\Exports\LocationsExport;
use App\Http\Controllers\Controller;
use App\Imports\LocationImport;
use App\Models\Location;
use Exception;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class LocationController extends Controller
{
    public function index(){
        return view('master.location');
    }

    public function getLocations(Request $request){
        $locations = Location::all();

        if($request->ajax()){
            return DataTables::of($locations)
            ->addIndexColumn()

            ->addColumn('action', function ($row){
                return '<a href="javascript:void(0)" class="btn btn-info btn-sm editButton" data-id='. encrypt($row->id).' data-name="' . $row->name .'"  data-bs-toggle="modal" data-bs-target="#editLocationModal">Edit</a>
                        <a href="javascript:void(0)" class="btn btn-danger btn-sm deleteButton" data-id="'. encrypt($row->id) .'" data-name="'. $row->name .'">Delete</a>
                ';
            })
            ->make(true);
        }
    }

    public function store(Request $request){
        try{
            if($request->file()){
                $request->validate([
                    'excelLocation' => 'required|mimes:xlsx,xls,csv|max:2048',
                ]);

                // dd($request->file('excelSize')->getClientOriginalExtension());

                Excel::import(new LocationImport, $request->file('excelLocation'));
            }
            elseif($request->location){

                $request->validate([
                    'location' => 'required'
                ]);

                Location::create([
                    'name' => $request->location
                ]);
            }

            return response()->json([
                'status' => 200,
                'message' => 'Location Stored Successfully'
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

    public function update(Request $request){
        // dd($request->all());
        try{
            $request->validate([
                'location' => 'required'
            ]);

            Location::whereId(decrypt($request->id))->update([
                'name' => $request->location
            ]);

            return response()->json([
                'status' => 200,
                'message' => 'Location Updated Successfully'
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

            $location = Location::find(decrypt($request->id));

            if($location){
                $location->delete();
                return response()->json([
                    'status' => 200,
                    'message' => 'Location Deleted Successfully!',
                ], 200);
            }else{
                return response()->json([
                    'status' => 404,
                    'message' => 'Location Not Found!',
                ], 404);
            }
        }catch(Exception $e){
            return response()->json([
                'status' => 500,
                'message' => 'Something Went Wrong '. $e->getMessage(),
            ]);
        }
    }

    public function locationExcelExport(){
        return Excel::download(new LocationsExport, 'locations.xlsx');
    }
}

