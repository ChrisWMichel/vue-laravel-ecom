<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Stripe\Stripe;
use App\Models\Order;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use Illuminate\Support\Facades\Log;

class StripeController extends Controller
{
    public function storeUserOrders(Request $request){
        
        foreach($request->cartItems as $item){
            $order = Order::create([
                'qty' => $item['qty'],
                'user_id' => $request->user_id,
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
         Stripe::setApiKey(config('services.stripe.secret'));
       //Stripe::setApiKey(env('STRIPE_SECRET'));   
       try{
        $checkout_session = Session::create([
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => 'T-shirts Store',
                    ],
                    'unit_amount' => $this->CalcTotalToPay($request->cartItems),
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' =>  $request->success_url,
            ]);
            return response()->json(['url' => $checkout_session->url]);
       } catch(\Exception $e){ 

              return response()->json(['error' => $e->getMessage()]);
       }
    }


    private function CalcTotalToPay($items){
        $total = 0;
        foreach($items as $item){
            $total += $this->OrderTotal($item['qty'], $item['price'], $item['coupon_id']);
        }
        return round($total * 100);
    }
    
}


