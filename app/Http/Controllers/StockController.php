<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stock;
use App\Models\Product;

class StockController extends Controller
{
    public function index(Request $request)
    {
        \Log::info('CSRF Token:', ['token' => $request->header('X-CSRF-TOKEN')]);

        $stocks = Stock::with(['product.brand', 'product.supplier'])->orderByDesc('product_id')->get();

        return response()->json([
            'stocks' => $stocks,
        ]);
    }

    public function show($id)
    {
        $stock = Stock::with(['product.brand', 'product.supplier'])->find($id);
        return response()->json($stock);
    }

    public function edit($id)
    {
        $stock = Stock::with(['product.brand', 'product.supplier'])->find($id);

        return response()->json([
            'stock' => $stock,
        ]);
    }

    public function update(Request $request, $id)
    {
        $stock = Stock::find($id);
        $stock->quantity = $request->quantity;

        $stock->save();

        return response()->json(["success" => "Stock updated successfully.", "stock" => $stock, "status" => 200]);
    }
}
