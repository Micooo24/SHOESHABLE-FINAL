<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Mail\OrderDeliveredReceipt;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with([
            'customer.user',
            'products',
            'courier'
        ])->orderByDesc('id')->get();

        return response()->json([
            'orders' => $orders,
        ]);
    }

    public function updateStatus(Request $request, $orderId)
    {
        try {
            $order = Order::with('customer.user')->findOrFail($orderId);

            if ($order->status == 'Processing' && $request->status == 'Shipped') {
                $order->status = $request->status;
            } elseif ($order->status == 'Shipped' && $request->status == 'Delivered') {
                $order->status = $request->status;
                $order->date_delivered = now();
                if ($order->customer && $order->customer->user && $order->customer->user->email) {
                    Mail::to($order->customer->user->email)->send(new OrderDeliveredReceipt($order));
                } else {
                    return response()->json(['status' => 'failed', 'message' => 'Customer email not found or invalid'], 400);
                }
            } elseif ($request->status == 'Processing') {
                return response()->json(['status' => 'failed', 'message' => 'Cannot revert to Processing'], 400);
            } elseif ($request->status == 'Cancelled') {
                $order->status = $request->status;
                $order->save();
                return response()->json(['status' => 'success']);
            } else {
                return response()->json(['status' => 'failed', 'message' => 'Invalid status transition'], 400);
            }
            $order->save();
            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'failed', 'message' => $e->getMessage()], 500);
        }
    }
}
