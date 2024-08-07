<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\Product;

class ChartController extends Controller
{
    public function charts()
    {
        return view('admin.dashboard');
    }

    public function productSales()
    {
        $productsWithRatings = DB::table('reviews')
            ->join('products', 'reviews.product_id', '=', 'products.id')
            ->select('products.id as id', 'products.name as product_name', DB::raw('AVG(reviews.ratings) as average_rating'))
            ->groupBy('products.id', 'products.name')
            ->orderBy('average_rating', 'DESC')
            ->get();
        $labels = $productsWithRatings->pluck('product_name')->toArray();
        $data = $productsWithRatings->pluck('average_rating')->toArray();

        return response()->json([
            'data' => $data,
            'labels' => $labels
        ]);
    }

    public function stockChart() {
        $stockData = Product::join('stocks', 'products.id', '=', 'stocks.product_id')
            ->select('products.name', DB::raw('SUM(stocks.quantity) as total_stock'))
            ->groupBy('products.name')
            ->orderBy('total_stock', 'asc')
            ->get();

        $labels = $stockData->pluck('name')->toArray();
        $data = $stockData->pluck('total_stock')->toArray();

        return response()->json(['data' => $data, 'labels' => $labels]);
    }

    public function productChart() {
        $products = DB::table('order_product AS op')
            ->join('products AS p', 'op.product_id', '=', 'p.id')
            ->join('brands AS b', 'p.brand_id', '=', 'b.id')
            ->select('b.brand_name', DB::raw('SUM(op.quantity) AS total'))
            ->groupBy('b.brand_name')
            ->orderBy('total', 'DESC')
            ->pluck('total', 'brand_name');
        $labels = $products->keys();
        $data = $products->values();
        return response()->json(['data' => $data, 'labels' => $labels]);
    }

    public function salesChart()
    {
        $sales = DB::table('orders AS o')
            ->join('order_product AS op', 'o.id', '=', 'op.order_id')
            ->join('products AS p', 'op.product_id', '=', 'p.id')
            ->orderBy(DB::raw('month(o.date_placed)'), 'ASC')
            ->groupBy(DB::raw('monthname(o.date_placed)'))
            ->pluck(
                DB::raw('sum(op.quantity * p.cost) AS total'),
                DB::raw('monthname(o.date_placed) AS month')
            )
            ->all();
        $labels = (array_keys($sales));
        $data = array_values($sales);
        return response()->json(array('data' => $data, 'labels' => $labels));
    }
}
