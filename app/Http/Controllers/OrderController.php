<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        $orders = [];

        switch ($user->role) {
            case 'customer':
                $orders = Order::where('customer_id', $user->id)->latest()->get();
                break;
            case 'retailer':
                $orders = Order::where(function($query) use ($user) {
                    $query->where('retailer_id', $user->id)
                          ->orWhere('user_id', $user->id);
                })->latest()->get();
                break;
            case 'wholesaler':
                $orders = Order::where(function($query) use ($user) {
                    $query->where('wholesaler_id', $user->id)
                          ->orWhere('user_id', $user->id);
                })->latest()->get();
                break;
            case 'vendor':
                $orders = Order::where('vendor_id', $user->id)->latest()->get();
                break;
        }

        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        $user = Auth::user();
        
        // All users can only order sunflower oil (finished product)
        $products = Product::where('category', 'finished_product')->get();
        
        // Determine available sellers based on user role
        $sellers = $this->getAvailableSellers($user->role);
        
        return view('orders.create', compact('products', 'sellers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'seller_id' => 'required|exists:users,id'
        ]);

        try {
            DB::beginTransaction();

            $user = Auth::user();
            $product = Product::findOrFail($request->product_id);
            $seller = User::findOrFail($request->seller_id);

            // Validate that the user is ordering sunflower oil
            if ($product->category !== 'finished_product') {
                throw new \Exception('Only sunflower oil orders are allowed.');
            }

            $order = new Order();
            $order->user_id = $user->id;
            $order->product_id = $request->product_id;
            $order->quantity = $request->quantity;
            $order->total_amount = $product->price * $request->quantity;
            $order->status = 'pending';

            // Set order type and roles based on user role
            switch ($user->role) {
                case 'customer':
                    $order->order_type = 'customer_to_retailer';
                    $order->customer_id = $user->id;
                    $order->retailer_id = $seller->id;
                    break;
                case 'retailer':
                    $order->order_type = 'retailer_to_wholesaler';
                    $order->retailer_id = $user->id;
                    $order->wholesaler_id = $seller->id;
                    break;
                case 'wholesaler':
                    $order->order_type = 'wholesaler_to_vendor';
                    $order->wholesaler_id = $user->id;
                    $order->vendor_id = $seller->id;
                    break;
            }

            $order->save();

            // Create payment transaction
            $order->paymentTransactions()->create([
                'amount' => $order->total_amount,
                'status' => 'pending',
                'transaction_date' => now()
            ]);

            DB::commit();

            return redirect()->route('orders.show', $order)
                           ->with('success', 'Order placed successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage() ?: 'Failed to place order. Please try again.');
        }
    }

    public function show(Order $order)
    {
        $this->authorize('view', $order);
        return view('orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipping,delivered,cancelled'
        ]);

        $this->authorize('update', $order);

        $order->status = $request->status;
        $order->save();

        // Create shipment log if status is shipping
        if ($request->status === 'shipping') {
            $order->shipmentLogs()->create([
                'from_location' => $this->getLocationByRole(Auth::user()->role),
                'to_location' => $this->getNextLocationByOrderType($order->order_type),
                'shipment_date' => now(),
                'status' => 'shipped'
            ]);
        }

        return back()->with('success', 'Order status updated successfully!');
    }

    public function supplierCoordination()
    {
        $user = Auth::user();
        
        if ($user->role !== 'wholesaler') {
            abort(403, 'Unauthorized action.');
        }

        $vendors = User::where('role', 'vendor')->get();
        $pendingOrders = Order::where('wholesaler_id', $user->id)
                            ->where('order_type', 'wholesaler_to_vendor')
                            ->whereIn('status', ['pending', 'processing'])
                            ->latest()
                            ->get();

        return view('market.supplier-coordination', compact('vendors', 'pendingOrders'));
    }

    private function getAvailableSellers($userRole)
    {
        switch ($userRole) {
            case 'customer':
                return User::where('role', 'retailer')->get();
            case 'retailer':
                return User::where('role', 'wholesaler')->get();
            case 'wholesaler':
                return User::where('role', 'vendor')
                          ->where('status', 'approved')  // Only show approved vendors
                          ->where('is_active', true)     // Only show active vendors
                          ->get();
            default:
                return collect();
        }
    }

    private function getLocationByRole($role)
    {
        return [
            'vendor' => 'Factory',
            'wholesaler' => 'Warehouse',
            'retailer' => 'Store'
        ][$role] ?? 'Unknown';
    }

    private function getNextLocationByOrderType($orderType)
    {
        return [
            'wholesaler_to_vendor' => 'Warehouse',
            'retailer_to_wholesaler' => 'Store',
            'customer_to_retailer' => 'Customer'
        ][$orderType] ?? 'Unknown';
    }
} 