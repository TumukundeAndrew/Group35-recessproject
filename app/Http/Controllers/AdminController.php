<?php

namespace App\Http\Controllers;

use App\Models\Worker;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    public function workforce()
    {
        $workers = Worker::latest()->paginate(10);
        return view('admin.workforce', compact('workers'));
    }

    // ... other existing methods ...
} 