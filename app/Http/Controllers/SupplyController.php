<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupplyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function application()
    {
        return view('supply.application');
    }

    public function validationStatus()
    {
        return view('supply.validation-status');
    }

    public function chat()
    {
        return view('supply.chat');
    }

    public function inventory()
    {
        return view('supply.inventory');
    }

    public function stockUpdate()
    {
        return view('supply.stock-update');
    }

    public function chatManufacturer()
    {
        return view('supply.chat-manufacturer');
    }

    public function reports()
    {
        return view('supply.reports');
    }
} 