<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Services\OrdersService;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrdersService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index()
    {
        //$orders = Order::with(['products', 'user', 'coupon'])->latest()->paginate(10);
        //$totalOrders = Order::count();
        $orders = $this->orderService->getOrdersWithRelations();
        $totalOrders = $this->orderService->getTotalOrdersCount();


        return Inertia::render('admin/orders/index', [
            'orders' => $orders,
            'totalOrders' => $totalOrders
        ]);
    }

    public function updateDeliveredAtDate(Order $order){
        $order->update([
            'delivered_at' => Carbon::now()
        ]);

        return redirect()->back()->with('success', 'Order delivered successfully');
    }

    public function delete(Order $order){
        $order->delete();

        return redirect()->back()->with('success', 'Order deleted successfully');
    }
}
