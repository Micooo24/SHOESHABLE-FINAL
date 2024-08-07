<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    // public function myOrder()
    // {
    //     $user = Auth::user();
    //     if ($user && $user->customer) {
    //         $orders = $user->customer->orders()->with(['products.brand'])->get();
    //         $orders->each(function ($order) {
    //             $order->product_names = $order->products->pluck('name')->implode(',');
    //             $order->total_amount = $order->products->sum(function ($product) {
    //                 return $product->pivot->quantity * $product->cost;
    //             });
    //             $order->products->each(function ($product) {
    //                 $product->brand_name = $product->brand->brand_name;
    //             });
    //         });
    //         return response()->json(['orders' => $orders], 200);
    //     } else {
    //         return response()->json(['error' => 'No orders found for the logged-in customer.'], 404);
    //     }
    // }

    public function myOrder()
    {
        $user = Auth::user();
        if ($user && $user->customer) {
            $orders = $user->customer->orders()->with(['products.brand'])->get();
            $orders->each(function ($order) {
                $order->product_names = $order->products->pluck('name')->implode(',');
                $order->total_amount = $order->products->sum(function ($product) {
                    return $product->pivot->quantity * $product->cost;
                });
                $order->products->each(function ($product) {
                    $product->brand_name = $product->brand->brand_name;
                });
            });
            return response()->json(['orders' => $orders], 200);
        } else {
            return response()->json(['error' => 'No orders found for the logged-in customer.'], 404);
        }
    }


    public function cancelOrder($id)
    {
        $user = Auth::user();
        $order = Order::where('id', $id)->whereHas('customer', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->first();

        if ($order && $order->status === 'Processing') {
            $order->products->each(function ($product) use ($order) {
                $product->stocks()->increment('quantity', $product->pivot->quantity);
            });
            $order->status = 'Cancelled';
            $order->save();

            return response()->json(['success' => 'Order cancelled successfully.'], 200);
        } else {
            return response()->json(['error' => 'Unable to cancel order.'], 400);
        }
    }

    public function getOrders(Request $request)
    {
        $status = $request->query('status');
        $query = Order::with('products');

        if ($status) {
            $query->where('status', $status);
        }

        $orders = $query->get();
        return response()->json(['orders' => $orders]);
    }

    public function getProfile()
    {
        try {
            $user = Auth::user()->load('customer');

            $userData = [
                'name' => $user->name,
                'email' => $user->email,
                'username' => $user->customer->username,
                'contact_number' => $user->customer->contact_number,
                'address' => $user->customer->address
            ];

            return response()->json([
                'status' => true,
                'data' => $userData
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function editProfile(Request $request)
    {
        $user = Auth::user()->load('customer'); // Get the authenticated user
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'username' => 'required|string|max:255|unique:users,username,'.$user->id,
            'contact_number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->customer->username = $validatedData['username'];
        $user->customer->contact_number = $validatedData['contact_number'];
        $user->customer->address = $validatedData['address'];
        $user->save();
        return response()->json(['message' => 'Profile updated successfully'], 200);
    }

    // public function update(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'password' => 'nullable|string|min:8|confirmed',
    //         'image' => 'nullable|image|max:2048',
    //     ]);

    //     $user = Auth::user();
    //     $user->name = $request->input('name');
    //     if ($request->has('password')) {
    //         $user->password = Hash::make($request->input('password'));
    //     }
    //     if ($request->hasFile('image')) {
    //         $image = $request->file('image');
    //         $path = $image->store('public/images');
    //         $user->image = str_replace('public/', 'storage/', $path);
    //     }

    //     if ($user->customer) {
    //         $customer = $user->customer;
    //         $customer->username = $request->input('username');
    //         $customer->address = $request->input('address');
    //         $customer->contact_number = $request->input('contact_number');
    //         $customer->save();
    //     }
    //     $user->save();
    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Profile updated successfully',
    //         'token' => $user->createToken("API TOKEN")->plainTextToken
    //     ]);
    // }
    public function update(Request $request)
    {
        // Validate input
        $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'nullable|string|min:8|confirmed',
            'image' => 'nullable|image|max:2048',
        ]);

        // Get the authenticated user
        $user = Auth::user();

        // Update user's name
        $user->name = $request->input('name');

        // Check if a new password is provided and update it
        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }

        // Check if an image is uploaded and update it
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $path = $image->store('public/images');
            $user->image = str_replace('public/', 'storage/', $path);
        }

        // Update customer's additional info
        if ($user->customer) {
            $customer = $user->customer;
            $customer->username = $request->input('username');
            $customer->address = $request->input('address');
            $customer->contact_number = $request->input('contact_number');
            $customer->save();
        }

        // Save the user
        $user->save();

        // Return response
        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'token' => $user->createToken("API TOKEN")->plainTextToken
        ]);
    }

    public function deactivateAccount(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            $user->status = 'inactive';
            $user->save();
            $user->tokens()->delete();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return response()->json(['message' => 'Account deactivated successfully']);
        }
        return response()->json(['message' => 'User not authenticated'], 401);
    }
}
