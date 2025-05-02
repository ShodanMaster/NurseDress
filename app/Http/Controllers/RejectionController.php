<?php

namespace App\Http\Controllers;

use App\Models\Grn;
use Illuminate\Http\Request;

class RejectionController extends Controller
{
    public function index()
    {
        $grnNumbers = Grn::where('qc_status', 1)->get();
        return view('transactions.rejection', compact('grnNumbers'));
    }

    public function store(Request $request)
    {
        // Handle the rejection logic here
        // You can access the form data using $request->input('field_name')
        // Perform any necessary validation and processing

        // Example response
        return response()->json(['message' => 'Rejection processed successfully']);
    }
}
