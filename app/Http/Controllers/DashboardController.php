<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        $role = $user->role;
        
        // Check if user has the appropriate role
        if (!in_array($role, ['admin', 'vendor', 'supplier', 'wholesaler', 'retailer', 'customer'])) {
            return redirect()->route('login')->with('error', 'Invalid role');
        }
        
        // Redirect to appropriate dashboard based on role
        switch ($role) {
            case 'admin':
                return $this->adminDashboard();
            
            case 'vendor':
            case 'supplier':
                return $this->supplyDashboard();
            
            case 'wholesaler':
            case 'retailer':
            case 'customer':
                return $this->marketDashboard();
            
            default:
                return redirect()->route('login')->with('error', 'Invalid role');
        }
    }

    protected function adminDashboard()
    {
        $data = [
            'pendingValidations' => Vendor::where('application_status', 'pending')->count(),
            'activeUsers' => \App\Models\User::count(),
            'todayOrders' => Order::whereDate('created_at', today())->count(),
            'recentActivity' => [] // Temporarily set to empty array until we set up the Activity model
        ];

        return view('dashboards.admin.index', $data);
    }

    protected function supplyDashboard()
    {
        $user = Auth::user();
        $data = [];

        if ($user->role === 'vendor') {
            $vendor = $user->vendor;
            $data = [
                'applicationStatus' => $vendor ? $vendor->application_status : 'Not Submitted',
                'requiredDocs' => $vendor ? ($vendor->regulatory_compliance ? 'Complete' : 'Incomplete') : 'Not Started',
                'recentActivity' => [] // Temporarily empty until Activity model is set up
            ];
        } else {
            $data = [
                'pendingOrders' => Order::where('supplier_id', $user->id)
                    ->whereIn('status', ['pending', 'processing'])
                    ->count(),
                'stockStatus' => $this->calculateStockStatus($user->id),
                'activeShipments' => Order::where('supplier_id', $user->id)
                    ->where('status', 'shipping')
                    ->count(),
                'recentActivity' => [] // Temporarily empty until Activity model is set up
            ];
        }

        return view('dashboards.supply.index', $data);
    }

    protected function marketDashboard()
    {
        $user = Auth::user();
        $data = [];

        switch ($user->role) {
            case 'wholesaler':
                $data = [
                    'pendingOrders' => Order::where('wholesaler_id', $user->id)
                        ->whereIn('status', ['pending', 'processing'])
                        ->count(),
                    'stockLevel' => $this->calculateStockLevel($user->id),
                    'recentActivity' => [] // Temporarily empty until Activity model is set up
                ];
                break;

            case 'retailer':
                $data = [
                    'todaySales' => Order::where('retailer_id', $user->id)
                        ->whereDate('created_at', today())
                        ->sum('total_amount'),
                    'lowStockItems' => $this->getLowStockItems($user->id),
                    'recentActivity' => [] // Temporarily empty until Activity model is set up
                ];
                break;

            case 'customer':
                $data = [
                    'activeOrders' => Order::where('customer_id', $user->id)
                        ->whereIn('status', ['pending', 'processing', 'shipping'])
                        ->count(),
                    'recentPurchases' => Order::where('customer_id', $user->id)
                        ->where('created_at', '>=', now()->subDays(30))
                        ->count(),
                    'recentActivity' => [] // Temporarily empty until Activity model is set up
                ];
                break;
        }

        return view('dashboards.market.index', $data);
    }

    private function calculateStockStatus($supplierId)
    {
        // Implement stock status calculation logic
        return '75%';
    }

    private function calculateStockLevel($wholesalerId)
    {
        // Implement stock level calculation logic
        return '65%';
    }

    private function getLowStockItems($retailerId)
    {
        // Implement low stock items calculation logic
        return 5;
    }
}
