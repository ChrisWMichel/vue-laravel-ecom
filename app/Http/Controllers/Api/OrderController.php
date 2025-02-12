<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use App\Models\Coupon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Stripe\Stripe;

class OrderController extends Controller
{
    public function storeUserOrders(Request $request){
        foreach($request->cartItems as $item){
            $order = Order::create([
                'qty' => $item['qty'],
                'user_id' => $request->user()->id,
                'coupon_id' => $item['coupon_id'],
                'total' => $this->OrderTotal($item['qty'], $item['price'], $item['coupon_id'])
            ]);
            $order->products()->attach($item['product_id']);
        }
        return response()->json(['message' => 'Order placed successfully', 'user' => $request->user()]);
    }

    public function OrderTotal($qty, $price, $coupon_id){
        $total = $qty * $price;
        $taxes = .035 * $total;
        $coupon = Coupon::find($coupon_id);
        if($coupon && $coupon->checkIfValid()){
            
            $discount = $coupon->discount * $total / 100;
            $total = ($total - $discount) + $taxes;
        }
        return $total;
    }

    public function payOrdersByStripe(Request $request){
       Stripe::setApiKey(env('STRIPE_SECRET'));
        // $order = Order::find($request->order_id);
        // $order->delivered_at = now();
        // $order->save();
        // return response()->json(['message' => 'Order paid successfully', 'order' => $order]);
    }
}


