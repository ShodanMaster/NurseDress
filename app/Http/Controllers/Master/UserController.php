<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    public function index(){
        return view('master.user');
    }

    public function getUsers(Request $request){
        $users = User::all();

        if($request->ajax()){
            return DataTables::of($users)
            ->addIndexColumn()

            ->addColumn('action', function ($row){
                return '<a href="javascript:void(0)" class="btn btn-info btn-sm editButton" data-id='. encrypt($row->id).' data-username="' . $row->name .'" data-type="' . $row->type .'"  data-bs-toggle="modal" data-bs-target="#editUserModal">Edit</a>
                        <a href="javascript:void(0)" class="btn btn-danger btn-sm deleteButton" data-id="'. encrypt($row->id) .'" data-username="'. $row->name .'">Delete</a>
                ';
            })
            ->make(true);
        }
    }

    public function store(Request $request){
        try{

            $request->validate([
                'username' => 'required|max:255,unique:userss',
                'password' => 'required|min:8|confirmed',
                'type' => 'required|in:admin,users',
            ]);

            User::create([
                'username' => $request->username,
                'password' => bcrypt($request->password),
                'type' => $request->type,
            ]);

            return response()->json([
                'status' => 200,
                'message' => 'User Stored Successfully'
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
                'username' => 'required|max:255,unique:users',
                'password' => 'nullable|min:8|confirmed',
                'type' => 'required|in:admin,user',
            ]);

            $user = User::find(decrypt($request->id));

            if($user){

                $user->username = $request->username;

                $user->type = $request->type;


                if($request->password){
                    $user->password = $request->password;
                }

                $user->save();
            }

            return response()->json([
                'status' => 200,
                'message' => 'User Updated Successfully'
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

            $user = User::find(decrypt($request->id));

            if($user){
                $user->delete();
                return response()->json([
                    'status' => 200,
                    'message' => 'User Deleted Successfully!',
                ], 200);
            }else{
                return response()->json([
                    'status' => 404,
                    'message' => 'User Not Found!',
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

