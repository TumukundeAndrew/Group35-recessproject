<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function apply(Request $request)
    {
        return response()->json(['message' => 'Vendor application submitted']);
    }
}
