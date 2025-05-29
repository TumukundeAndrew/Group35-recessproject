<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the order.
     */
    public function view(User $user, Order $order)
    {
        // User can view if they are:
        // 1. The order creator
        // 2. The customer of the order
        // 3. The retailer of the order
        // 4. The wholesaler of the order
        // 5. The vendor of the order
        return $user->id === $order->user_id ||
               $user->id === $order->customer_id ||
               $user->id === $order->retailer_id ||
               $user->id === $order->wholesaler_id ||
               $user->id === $order->vendor_id;
    }

    /**
     * Determine whether the user can update the order.
     */
    public function update(User $user, Order $order)
    {
        // Only the seller can update the order status
        switch ($order->order_type) {
            case 'customer_to_retailer':
                return $user->id === $order->retailer_id;
            case 'retailer_to_wholesaler':
                return $user->id === $order->wholesaler_id;
            case 'wholesaler_to_vendor':
                return $user->id === $order->vendor_id;
            default:
                return false;
        }
    }

    /**
     * Determine whether the user can create orders.
     */
    public function create(User $user)
    {
        // All authenticated users can create orders
        return true;
    }

    /**
     * Determine whether the user can delete orders.
     */
    public function delete(User $user, Order $order)
    {
        // Only allow deletion of pending orders by the order creator
        return $user->id === $order->user_id && $order->status === 'pending';
    }
} 