<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wishlist;
use App\Models\Product;
use DB;

class WishlistController extends Controller
{
    public function index()
    {
        $customerId = auth()->id();

        $wishlists = Wishlist::where('customer_id', $customerId)
            ->with(['product' => function ($query) {
                $query->select('id', 'name','cost', 'img_path');
            }])
            ->get();

        foreach ($wishlists as $wishlist) {
            $wishlist->product->quantity = \DB::table('stocks')
                ->where('product_id', $wishlist->product->id)
                ->value('quantity');
        }

        return response()->json([
            'status' => 'success',
            'wishlists' => $wishlists,
        ], 200);
    }


    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
        ]);

        if (!auth()->check()) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not authenticated!',
            ], 401);
        }

        $exists = Wishlist::where('customer_id', auth()->id())
                          ->where('product_id', $request->product_id)
                          ->exists();

        if ($exists) {
            return response()->json([
                'status' => 'warning',
                'message' => 'Product already in wishlist!',
            ], 409);
        }

        try {
            $wishlist = Wishlist::create([
                'customer_id' => auth()->id(),
                'product_id' => $request->product_id,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Product added to wishlist!',
                'wishlist' => $wishlist,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while adding the product to the wishlist.',
            ], 500);
        }
    }

    // public function check(Request $request)
    // {
    //     $request->validate([
    //         'product_id' => 'required|integer|exists:products,id',
    //     ]);

    //     if (!auth()->check()) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'User not authenticated!',
    //         ], 401);
    //     }
    //     $exists = Wishlist::where('customer_id', auth()->id())
    //                       ->where('product_id', $request->product_id)
    //                       ->exists();

    //     return response()->json([
    //         'exists' => $exists,
    //     ], 200);
    // }

    public function remove($id)
    {
        $wishlist = Wishlist::where('customer_id', auth()->id())
                            ->where('product_id', $id)
                            ->first();

        if (!$wishlist) {
            return response()->json([
                'status' => 'error',
                'message' => 'Item not found in wishlist!',
            ], 404);
        }

        try {
            $wishlist->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Item removed from wishlist!',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while removing the item from the wishlist.',
            ], 500);
        }
    }
}
