<?php

namespace App\Http\Controllers\Api;

use App\Models\Coupon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CouponController extends Controller
{
    public function applyCoupon(Request $request)
    {
        $coupon = Coupon::whereName($request->name)->first();
        if (!$coupon) {
            return response()->json(['error' => 'Invalid coupon code'], 422);
        }

        if (!$coupon->checkIfValid()) {
            return response()->json(['message' => 'Coupon is expired'], 422);
        }

        return response()->json(['message' => 'Coupon applied successfully', 'coupon' => $coupon]);
    }
}
