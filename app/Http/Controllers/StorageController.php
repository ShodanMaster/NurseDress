<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StorageController extends Controller
{
    public function rejection(){
        return view('transactions.rejection');
    }

    public function store(Request $request){
        dd($request->all());
    }
}
