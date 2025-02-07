<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use Inertia\Inertia;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\AuthAdminRequest;

class AdminController extends Controller
{
    public function index()
    {
        $todayOrders = Order::whereDay('created_at', Carbon::today())->get();
        $yesterdayOrders = Order::whereDay('created_at', Carbon::yesterday())->get();
        $monthsOrders = Order::whereMonth('created_at', Carbon::now()->month)->get();
        $yearOrders = Order::whereYear('created_at', Carbon::now()->year)->get();

        $admin = auth()->guard('admin')->user();

        return Inertia::render('admin/dashboard', [
            'todayOrders' => $todayOrders,
            'yesterdayOrders' => $yesterdayOrders,
            'monthsOrders' => $monthsOrders,
            'yearOrders' => $yearOrders,
            'admin' => $admin
        ]);
    }

    /**
     * Display the login form
     */
    public function login()
    {
        if(!auth()->guard('admin')->check()) {
            return Inertia::render('admin/login');
        }
        return redirect()->route('admin.index');
    }
    /**
     * Login admin
     */
    public function auth(AuthAdminRequest $request)
    {
        if($request->validated()) {
            if(auth()->guard('admin')->attempt([
                'email' => $request->email,
                'password' => $request->password,
            ])) {

                $request->session()->regenerate();
                return redirect()->route('admin.index');
            }else {
                // return Inertia::render('admin.login')->with([
                //     'error' => 'These credentials do not match any of our records.'
                // ]);
                return back()->withErrors([
                    'email' => 'These credentials do not match any of our records.xxxx',
                ]);
            }
        }
    }

    public function logout(Request $request)
    {
        auth()->guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }


}
