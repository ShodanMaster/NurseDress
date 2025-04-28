<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

use function PHPSTORM_META\type;

class EmployeeController extends Controller
{
    public function index(){
        return view('master.employee');
    }

    public function getEmployees(Request $request){
        $employees = Employee::all();

        if($request->ajax()){
            return DataTables::of($employees)
            ->addIndexColumn()

            ->addColumn('action', function ($row){
                return '<a href="javascript:void(0)" class="btn btn-info btn-sm editButton" data-id='. encrypt($row->id).' data-username="' . $row->name .'" data-type="' . $row->type .'"  data-bs-toggle="modal" data-bs-target="#editEmployeeModal">Edit</a>
                        <a href="javascript:void(0)" class="btn btn-danger btn-sm deleteButton" data-id="'. encrypt($row->id) .'" data-username="'. $row->name .'">Delete</a>
                ';
            })
            ->make(true);
        }
    }

    public function store(Request $request){
        try{

            $request->validate([
                'username' => 'required|max:255,unique:employees',
                'password' => 'required|min:8|confirmed',
                'type' => 'required|in:admin,employee',
            ]);

            Employee::create([
                'name' => $request->username,
                'password' => $request->password,
                'type' => $request->type,
            ]);

            return response()->json([
                'status' => 200,
                'message' => 'Employee Stored Successfully'
            ], 200);

        }catch (Exception $e) {

            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                $message = 'Duplicate entry found. Please ensure the data is unique.';
            } else {
                $message = 'Something Went Wrong:'.$e->getMessage();
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
                'username' => 'required|max:255,unique:employees',
                'password' => 'nullable|min:8|confirmed',
                'type' => 'required|in:admin,employee',
            ]);

            $employee = Employee::find(decrypt($request->id));

            if($employee){

                $employee->name = $request->username;

                $employee->type = $request->type;


                if($request->password){
                    $employee->password = $request->password;
                }

                $employee->save();
            }

            return response()->json([
                'status' => 200,
                'message' => 'Employee Updated Successfully'
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

            $employee = Employee::find(decrypt($request->id));

            if($employee){
                $employee->delete();
                return response()->json([
                    'status' => 200,
                    'message' => 'Employee Deleted Successfully!',
                ], 200);
            }else{
                return response()->json([
                    'status' => 404,
                    'message' => 'Employee Not Found!',
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
