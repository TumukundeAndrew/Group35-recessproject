<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use App\Services\MLPredictionService;

class AnalyticsController extends Controller
{
    protected $mlService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(MLPredictionService $mlService)
    {
        $this->middleware('auth');
        $this->mlService = $mlService;
    }

    public function demandPrediction()
    {
        return Response::json(['demand' => 'prediction data']);
    }

    public function customerSegments()
    {
        return Response::json(['segments' => 'customer data']);
    }

    public function index()
    {
        $user = Auth::user();
        
        try {
            $filePath = storage_path('app/data/sales.csv');
            if (!file_exists($filePath)) {
                throw new \Exception('Sales data file not found');
            }

            $data = array_map('str_getcsv', file($filePath));
            $header = array_shift($data);

            $sales = [];
            $totalQuantity = 0;
            $customerSpend = [];
            $customerFrequency = [];

            foreach ($data as $row) {
                [$orderId, $product, $customer, $quantity, $price, $date, $seller_type, $seller_id] = $row;
                $quantity = (int) $quantity;
                $price = (float) $price;

                // Filter data based on user role
                if ($user->role === 'admin' || 
                    ($user->role === 'retailer' && $seller_type === 'retailer' && $seller_id == $user->id) ||
                    ($user->role === 'wholesaler' && $seller_type === 'wholesaler' && $seller_id == $user->id)) {
                    
                    // Accumulate total quantity sold
                    $totalQuantity += $quantity;

                    // Track customer total spend
                    $customerSpend[$customer] = ($customerSpend[$customer] ?? 0) + ($quantity * $price);

                    // Track customer frequency
                    $customerFrequency[$customer] = ($customerFrequency[$customer] ?? 0) + 1;

                    $sales[] = compact('orderId', 'product', 'customer', 'quantity', 'price', 'date');
                }
            }

            // Generate ML predictions and recommendations
            $demandPredictions = $this->mlService->predictDemand($sales);
            $customerSegments = $this->mlService->segmentCustomers($sales);

            return view('dashboards.analytics.index', compact(
                'sales', 
                'totalQuantity', 
                'customerSpend', 
                'customerFrequency',
                'demandPredictions',
                'customerSegments'
            ));
        } catch (\Exception $e) {
            return view('dashboards.analytics.index', [
                'sales' => [],
                'totalQuantity' => 0,
                'customerSpend' => [],
                'customerFrequency' => [],
                'demandPredictions' => null,
                'customerSegments' => null,
                'error' => $e->getMessage()
            ]);
        }
    }
}
