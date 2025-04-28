<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function index(){
        return view('auth.login');
    }

    public function logingIn(Request $request){
        $credentials = $request->only('username', 'password');

        if (auth()->guard('employee')->attempt($credentials)) {
            return redirect()->intended('dashboard');
        }

        flash()->warning('Wrong Credentails');

        return redirect()->back();
    }

    public function loggingOut(){
        auth()->guard('employee')->logout();
        return redirect()->route('login')->with('success', 'logged Out');
    }
}
