<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class SupplyController extends Controller
{
    
  
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:supplier');
    }

    public function index()
    {
        return view('supplier.dashboard');
    }

    public function inventory()
    {
        return view('supplier.inventory');
    }

    public function orders()
    {
        return view('supplier.orders');
    }

    public function shipments()
    {
        return view('supplier.shipments');
    }

    public function chat()
    {
        return view('supplier.chat');

    }

    public function reports()
    {

        return view('supplier.reports');
    }
}

