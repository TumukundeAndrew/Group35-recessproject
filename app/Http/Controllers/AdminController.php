<?php

namespace App\Http\Controllers;

use App\Models\Worker;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    public function vendorValidation()
    {
        $pendingVendors = User::where('role', 'vendor')
                             ->where('status', 'pending')
                             ->latest()
                             ->paginate(10);
        return view('admin.vendor-validation', compact('pendingVendors'));
    }

    public function facilityVisits()
    {
        $scheduledVisits = [];  // You can implement the facility visits logic here
        return view('admin.facility-visits', compact('scheduledVisits'));
    }

    public function workforce()
    {
        $workers = Worker::latest()->paginate(10);
        return view('admin.workforce', compact('workers'));
    }

    public function reports()
    {
        $todayOrders = Order::whereDate('created_at', Carbon::today())->count();
        $pendingValidations = User::where('role', 'vendor')
                                 ->where('status', 'pending')
                                 ->count();
        $totalWorkforce = Worker::count();

        return view('admin.reports', compact('todayOrders', 'pendingValidations', 'totalWorkforce'));
    }

    // ... other existing methods ...
} 