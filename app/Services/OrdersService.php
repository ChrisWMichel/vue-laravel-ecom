<?php

namespace App\Services;

use App\Models\Order;

class OrdersService
{
    public function getOrdersWithRelations()
    {
        return Order::select('id', 'user_id', 'coupon_id', 'total', 'created_at', 'delivered_at')
            ->with([
                'products:id,name,price', 
                'user:id,name,email', 
                'coupon:id,name,discount'
            ])
            ->latest()
            ->paginate(10);
    }

    public function getTotalOrdersCount()
    {
        return Order::count();
    }
}
