<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\Coupon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddCouponRequest;
use App\Http\Requests\UpdateCouponRequest;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $coupons = Coupon::latest()->get()->map(function ($coupon) {
            return [
                'id' => $coupon->id,
                'name' => $coupon->name,
                'discount' => $coupon->discount,
                'valid_until' => $coupon->valid_until,
                'is_valid' => $coupon->checkIfValid(),
            ];
        });
        return Inertia::render('admin/coupons/index')->with([
            'coupons' => $coupons
         ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('admin/coupons/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AddCouponRequest $request)
    {
        if($request->validated()) {
            Coupon::create($request->validated());

            return redirect()->route('admin.coupons.index')->with('success', 'Coupon added successfully');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Coupon $coupon)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Coupon $coupon)
    {
        return Inertia::render('admin/coupons/edit')->with([
            'coupon' => $coupon
         ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCouponRequest $request, Coupon $coupon)
    {
        
        $coupon->update($request->validated());

            return redirect()->route('admin.coupons.index')->with('success', 'Coupon updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Coupon $coupon)
    {
        $coupon->delete();

        return redirect()->route('admin.coupons.index');
    }
}
