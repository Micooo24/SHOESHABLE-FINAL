<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use App\Models\Stock;
use App\Models\Product;

class DashboardController extends Controller
{
    public function getDashboardData()
    {
        try {
            $totalCustomers = User::where('role', 'customer')->count();
            $earnings = Order::with('products')->get()->sum(function ($order) {
                return $order->products->sum(function ($product) {
                    return $product->cost * $product->pivot->quantity;
                });
            });
            $totalTransactions = Order::count();

            $lowStockCount = Stock::where('quantity', '<=', 10)->count();

            $outOfStockCount = Product::whereHas('stocks', function ($query) {
                $query->where('quantity', 0);
            })->count();

            return response()->json([
                'status' => true,
                'totalCustomers' => $totalCustomers,
                'earnings' => $earnings,
                'totalTransactions' => $totalTransactions,
                'outOfStockCount' => $outOfStockCount,
                'lowStockCount' => $lowStockCount,
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
