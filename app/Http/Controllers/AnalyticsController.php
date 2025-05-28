<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function demandPrediction()
    {
        return response()->json(['demand' => 'prediction data']);
    }

    public function customerSegments()
    {
        return response()->json(['segments' => 'customer data']);
    }
}
