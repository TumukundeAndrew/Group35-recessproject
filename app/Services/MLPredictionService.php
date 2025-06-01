<?php

namespace App\Services;

use Carbon\Carbon;

class MLPredictionService
{
    /**
     * Predict future demand based on historical sales data
     */
    public function predictDemand($salesData)
    {
        // Group sales by date and calculate total quantity
        $dailySales = [];
        foreach ($salesData as $sale) {
            $date = $sale['date'];
            $dailySales[$date] = ($dailySales[$date] ?? 0) + $sale['quantity'];
        }

        // Sort by date
        ksort($dailySales);

        // Calculate moving average (7-day window)
        $movingAverages = [];
        $window = 7;
        $dates = array_keys($dailySales);
        $quantities = array_values($dailySales);

        for ($i = 0; $i < count($dates); $i++) {
            $start = max(0, $i - $window + 1);
            $windowData = array_slice($quantities, $start, min($window, $i + 1));
            $movingAverages[$dates[$i]] = round(array_sum($windowData) / count($windowData));
        }

        // Predict next 7 days
        $lastDate = end($dates);
        $predictions = [];
        $lastAverage = end($movingAverages);
        
        // Calculate trend
        $recentAverages = array_slice($movingAverages, -5);
        $trend = 0;
        if (count($recentAverages) > 1) {
            $trend = (end($recentAverages) - reset($recentAverages)) / count($recentAverages);
        }

        for ($i = 1; $i <= 7; $i++) {
            $nextDate = Carbon::parse($lastDate)->addDays($i)->format('Y-m-d');
            $predictedQuantity = max(0, round($lastAverage + ($trend * $i)));
            $predictions[$nextDate] = $predictedQuantity;
        }

        return [
            'historical' => $movingAverages,
            'predictions' => $predictions
        ];
    }

    /**
     * Segment customers based on purchasing patterns
     */
    public function segmentCustomers($salesData)
    {
        $customers = [];

        // Calculate RFM (Recency, Frequency, Monetary) metrics
        foreach ($salesData as $sale) {
            $customer = $sale['customer'];
            $date = Carbon::parse($sale['date']);
            $amount = $sale['quantity'] * $sale['price'];

            if (!isset($customers[$customer])) {
                $customers[$customer] = [
                    'last_purchase' => $date,
                    'frequency' => 0,
                    'total_spent' => 0,
                    'avg_order_value' => 0,
                    'purchases' => []
                ];
            }

            $customers[$customer]['last_purchase'] = max($customers[$customer]['last_purchase'], $date);
            $customers[$customer]['frequency']++;
            $customers[$customer]['total_spent'] += $amount;
            $customers[$customer]['purchases'][] = [
                'date' => $date,
                'amount' => $amount,
                'quantity' => $sale['quantity']
            ];
        }

        // Calculate average order value and determine segments
        $segments = [
            'high_value' => [],
            'loyal' => [],
            'potential' => [],
            'at_risk' => []
        ];

        $now = Carbon::now();
        foreach ($customers as $customer => $data) {
            $data['avg_order_value'] = $data['total_spent'] / $data['frequency'];
            $recency = $now->diffInDays($data['last_purchase']);

            // Segment based on RFM
            if ($data['total_spent'] > 1000 && $data['frequency'] > 3) {
                $segments['high_value'][] = [
                    'name' => $customer,
                    'metrics' => $data,
                    'recommendations' => [
                        'Offer VIP benefits and early access to new products',
                        'Provide personalized bulk purchase discounts',
                        'Send exclusive promotional offers'
                    ]
                ];
            } elseif ($data['frequency'] > 2 && $recency < 30) {
                $segments['loyal'][] = [
                    'name' => $customer,
                    'metrics' => $data,
                    'recommendations' => [
                        'Implement a loyalty rewards program',
                        'Offer moderate volume discounts',
                        'Send regular newsletters with product updates'
                    ]
                ];
            } elseif ($recency < 60 && $data['avg_order_value'] > 100) {
                $segments['potential'][] = [
                    'name' => $customer,
                    'metrics' => $data,
                    'recommendations' => [
                        'Provide first-time bulk purchase incentives',
                        'Share customer success stories',
                        'Offer free samples with next purchase'
                    ]
                ];
            } else {
                $segments['at_risk'][] = [
                    'name' => $customer,
                    'metrics' => $data,
                    'recommendations' => [
                        'Send re-engagement emails with special offers',
                        'Request feedback on previous purchases',
                        'Offer special "welcome back" discounts'
                    ]
                ];
            }
        }

        return $segments;
    }
} 