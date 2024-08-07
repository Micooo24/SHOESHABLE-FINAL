<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Stock;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['brand', 'supplier', 'stocks'])
        ->orderByDesc('id')
        ->get();
        $brands = Brand::all();
        $suppliers = Supplier::all();
        $stocks = Stock::all();

        return response()->json([
            'products' => $products,
            'brands' => $brands,
            'suppliers' => $suppliers,
            'stocks' => $stocks
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'brand_id' => 'required|exists:brands,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'description' => 'nullable|string',
            'cost' => 'required|numeric',
            'quantity' => 'required|numeric',
            'uploads.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);


        // Create a new product
        $product = new Product;
        $product->name = $request->name;
        $product->brand_id = $request->brand_id;
        $product->supplier_id = $request->supplier_id;
        $product->description = $request->description;
        $product->cost = $request->cost;
        $product->img_path = '';

        // Handle image uploads
        if ($request->hasFile('uploads')) {
            foreach ($request->file('uploads') as $file) {
                $fileName =$file->getClientOriginalName();
                $file->storeAs('public/images', $fileName);
                $product->img_path .= 'storage/images/' . $fileName . ',';
            }
            $product->img_path = rtrim($product->img_path, ',');
        }

        // Save product & stock
        $product->save();

        // Create stock for the product
            $stock = new Stock;
            $stock->quantity = $request->quantity;
            $stock->product_id = $product->id; // Assign product_id

        $stock ->save();


        // Return a success response with the created product
        return response()->json([
            "success" => "Product created successfully.",
            "product" => $product->load(['brand', 'supplier']),
            "stock"=> $stock,
            "status" => 200
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::with(['brand', 'supplier'])->find($id);

        $stock = Stock::all();

        if (!$product) {
            return response()->json(['error' => 'Product not found.', 'status' => 404]);
        }
        return response()->json([
            'product' => $product,
            'stock' => $stock,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'brand_id' => 'required|exists:brands,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'description' => 'nullable|string',
            'cost' => 'required|numeric',
            'uploads.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        $product = Product::find($id);

        if (!$product) {
            return response()->json(["error" => "Product not found.", "status" => 404]);
        }
        $product->name = $request->name;
        $product->brand_id = $request->brand_id;
        $product->supplier_id = $request->supplier_id;
        $product->description = $request->description;
        $product->cost = $request->cost;

        if ($request->hasFile('uploads')) {
            $imagePaths = [];
            foreach ($request->file('uploads') as $file) {
                $fileName = $file->getClientOriginalName();
                $file->storeAs('public/images', $fileName);
                $imagePaths[] = 'storage/images/' . $fileName;
            }
            $product->img_path = implode(',', $imagePaths);
        }
        $product->save();
        return response()->json([
            "success" => "Product updated successfully.",
            "product" => $product->load(['brand', 'supplier']),
            "status" => 200
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['error' => 'Product not found', 'status' => 404]);
        }
        Product::destroy($id);
        return response()->json(['success' => 'Product deleted', 'status' => 200]);
    }

    public function restoreProduct($id)
    {
        $product = Product::onlyTrashed()->find($id);
        if ($product) {
            $product->restore();
            return response()->json(['message' => 'Product restored successfully']);
        }
        return response()->json(['message' => 'Product not found'], 404);
    }

    public function getDeletedProducts()
    {
        $deletedProducts = Product::onlyTrashed()
            ->with(['brand', 'supplier', 'stocks'])
            ->orderByDesc('deleted_at')
            ->get();

        return response()->json([
            'products' => $deletedProducts
        ]);
    }
}
