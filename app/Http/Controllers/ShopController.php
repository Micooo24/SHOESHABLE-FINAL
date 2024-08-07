<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Courier;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Stock;
use App\Models\PaymentMethod;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
    // public function index(Request $request)
    // {
    //     $products = Product::whereHas('stocks', function ($query) {
    //         $query->where('quantity', '>', 0);
    //     })->with('brand', 'stocks')->paginate(10);

    //     return response()->json($products);
    // }

    public function index(Request $request)
    {
        $products = Product::whereHas('stocks', function ($query) {
            $query->where('quantity', '>', 0);
        })->with(['brand' => function($query) {
            $query->select('id', 'brand_name');
        }, 'stocks'])->paginate(10);

        return response()->json($products);
    }

    public function addToCart(Request $request)
    {
        // Log::info('Request headers:', $request->headers->all());
        $user = Auth::user();
        // Log::info('Authenticated user:', $user ? $user->toArray() : []);

        if (!$user) {
            Log::debug('User not authenticated', [
                'session' => session()->all(),
                'request' => $request->headers->all(),
            ]);
            return response()->json(['message' => 'User not authenticated'], 401);
        }

        $customer = $user->customer;

        if (!$customer) {
            return response()->json(['message' => 'Customer record not found. Please create a customer profile.'], 404);
        }

        $product_id = $request->input('product_id');
        $cart_qty = $request->input('cart_qty', 1);

        $cartItem = $customer->products()->where('product_id', $product_id)->first();

        if ($cartItem) {
            $customer->products()->updateExistingPivot($product_id, ['cart_qty' => $cartItem->pivot->cart_qty + $cart_qty]);
        } else {
            $customer->products()->attach($product_id, ['cart_qty' => $cart_qty]);
        }

        $updatedCartItem = $customer->products()->where('product_id', $product_id)->first();
        return response()->json(['message' => 'Product added to cart!', 'cartItem' => $updatedCartItem]);
    }

    public function getCarts()
    {
        $userId = Auth::id();

        // Fetch the customer associated with the authenticated user
        $customer = DB::table('customers')->where('user_id', $userId)->first();

        if ($customer) {
            // Fetch cart items with product details, stock quantities, and images
            $carts = DB::table('customer_product')
                ->join('products', 'customer_product.product_id', '=', 'products.id')
                ->leftJoin('stocks', 'products.id', '=', 'stocks.product_id')
                ->select('products.id', 'products.name', 'products.description', 'products.cost', 'products.img_path',
                    'customer_product.cart_qty as pivot_cart_qty', DB::raw('SUM(stocks.quantity) as total_stock'))
                ->where('customer_product.customer_id', $customer->id)
                ->groupBy('products.id', 'products.name', 'products.description', 'products.cost', 'products.img_path', 'customer_product.cart_qty')
                ->get()
                ->map(function ($item) use ($customer) {
                    // Get the first image URL from the comma-separated list
                    $images = explode(',', $item->img_path);
                    $firstImage = $images[0] ?? ''; // Fallback to an empty string if no images

                    return [
                        'id' => $item->id,
                        'name' => $item->name,
                        'description' => $item->description,
                        'cost' => $item->cost,
                        'img_path' => $firstImage, // Include the first image URL
                        'pivot_customer_id' => $customer->id,
                        'pivot_product_id' => $item->id,
                        'pivot_cart_qty' => $item->pivot_cart_qty,
                        'total_stock' => $item->total_stock,
                    ];
                });

            // Calculate the total cost of the cart
            $cartTotal = $carts->sum(function ($cart) {
                return $cart['cost'] * $cart['pivot_cart_qty'];
            });

            return response()->json([
                'carts' => $carts,
                'cartTotal' => $cartTotal
            ]);
        } else {
            return response()->json(['message' => 'Customer not found'], 404);
        }
    }

    public function updateCart(Request $request)
    {
        $data = $request->json()->all();
        $productId = $data['product_id'];
        $quantity = $data['cart_qty'];
        $customerId = auth()->user()->id;

        $product = Product::with('stocks')->find($productId);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $totalStockQuantity = $product->stocks->sum('quantity');

        \Log::info("Product ID: $productId, Requested Quantity: $quantity, Total Stock: $totalStockQuantity");

        if ($quantity > $totalStockQuantity) {
            return response()->json(['message' => 'Not enough stock available'], 400);
        }

        $updated = DB::table('customer_product')
            ->where('customer_id', $customerId)
            ->where('product_id', $productId)
            ->update(['cart_qty' => $quantity]);

        if ($updated) {
            return response()->json(['message' => 'Cart updated successfully']);
        }

        return response()->json(['message' => 'Cart item not found'], 404);
    }

    public function removeFromCart(Request $request)
    {
        $data = $request->json()->all();
        $productId = $data['product_id'];
        $customerId = auth()->user()->id;

        $deleted = DB::table('customer_product')
            ->where('customer_id', $customerId)
            ->where('product_id', $productId)
            ->delete();

        if ($deleted) {
            return response()->json(['message' => 'Cart item removed successfully']);
        }
        return response()->json(['message' => 'Cart item not found'], 404);
    }


    // public function checkout()
    // {
    //     $userId = Auth::id();
    //     $customer = Customer::where('user_id', $userId)->first();

    //     if ($customer) {
    //         // Fetch cart items with product details and stock quantities
    //         $carts = $customer->products()
    //             ->with('stocks')
    //             ->get()
    //             ->map(function ($product) {
    //                 $totalStock = $product->stocks->sum('quantity');

    //                 return [
    //                     'id' => $product->id,
    //                     'name' => $product->name,
    //                     'description' => $product->description,
    //                     'cost' => $product->cost,
    //                     'pivot_customer_id' => $product->pivot->customer_id,
    //                     'pivot_product_id' => $product->pivot->product_id,
    //                     'pivot_cart_qty' => $product->pivot->cart_qty,
    //                     'total_stock' => $totalStock,
    //                     'img_path' => $product->img_path, // Make sure this is handled properly
    //                 ];
    //             });

    //         $cartTotal = $carts->sum(function ($cart) {
    //             return $cart['cost'] * $cart['pivot_cart_qty'];
    //         });
    //         $couriers = DB::table('couriers')->get();
    //         $user = $customer->user;
    //         $customerDetails = $user ? [
    //             'name' => $user->name,
    //             'email' => $user->email,
    //             'address' => $customer->address ?? 'Address not provided',
    //             'contact_number' => $customer->contact_number,
    //         ] : [
    //             'name' => 'Name not available',
    //             'email' => 'Email not available',
    //             'address' => 'Address not available',
    //             'contact_number' => 'No Contact Number',
    //         ];

    //         return response()->json([
    //             'carts' => $carts,
    //             'couriers' => $couriers,
    //             'cartTotal' => $cartTotal,
    //             'customer' => $customerDetails
    //         ]);
    //     } else {
    //         return response()->json(['message' => 'Customer not found'], 404);
    //     }
    // }

    public function checkout()
    {
        $userId = Auth::id();
        $customer = Customer::where('user_id', $userId)->first();

        if ($customer) {
            // Fetch cart items with product details and stock quantities
            $carts = $customer->products()
                ->with('stocks')
                ->get()
                ->map(function ($product) {
                    $totalStock = $product->stocks->sum('quantity');

                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'description' => $product->description,
                        'cost' => $product->cost,
                        'pivot_customer_id' => $product->pivot->customer_id,
                        'pivot_product_id' => $product->pivot->product_id,
                        'pivot_cart_qty' => $product->pivot->cart_qty,
                        'total_stock' => $totalStock,
                        'img_path' => $product->img_path,
                    ];
                });

            $cartTotal = $carts->sum(function ($cart) {
                return $cart['cost'] * $cart['pivot_cart_qty'];
            });

            // Assuming you have a way to determine shipping fee
            $shippingFee = 50; // Replace with your logic to fetch or calculate the shipping fee

            $couriers = DB::table('couriers')->get();
            $payment_methods = DB::table('payment_methods')->get();

            $user = $customer->user;
            $customerDetails = $user ? [
                'name' => $user->name,
                'email' => $user->email,
                'address' => $customer->address ?? 'Address not provided',
                'contact_number' => $customer->contact_number,
            ] : [
                'name' => 'Name not available',
                'email' => 'Email not available',
                'address' => 'Address not available',
                'contact_number' => 'No Contact Number',
            ];

            return response()->json([
                'carts' => $carts,
                'couriers' => $couriers,
                'payment_methods' => $payment_methods,
                'cartTotal' => $cartTotal,
                'shippingFee' => $shippingFee,
                'totalAmount' => $cartTotal + $shippingFee,
                'customer' => $customerDetails
            ]);
        } else {
            return response()->json(['message' => 'Customer not found'], 404);
        }
    }

    public function placeOrder(Request $request) {
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'status' => 'Unauthorized',
                'code' => 401,
                'error' => 'User not authenticated'
            ], 401);
        }

        $customerId = $user->customer->id;

        $this->validate($request, [
            'courier_id' => 'required|integer',
            'payment_method_id' => 'required|integer',
            'items' => 'required|array',
            'items.*.id' => 'required|integer|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1'
        ]);

        try {
            DB::beginTransaction();

            $order = $this->createOrder($customerId, $request->courier_id);
            $this->processOrderItems($order, $request->input('items'));
            // $this->createPayment($order->id, $request->payment_method_id);

            Log::info('Payment Method ID:', ['payment_method_id' => $request->payment_method_id]);

            $this->createPayment($order->id, $request->payment_method_id);

            $user->customer->products()->detach();

            DB::commit();

            return response()->json([
                'status' => 'Order Success',
                'code' => 200,
                'orderId' => $order->id,
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Order failed: ' . $e->getMessage());

            return response()->json([
                'status' => 'Order failed',
                'code' => 409,
                'error' => $e->getMessage(),
            ], 409);
        }
    }

    private function createOrder($customerId, $courierId) {
        $order = new Order();
        $order->customer_id = $customerId;
        $order->date_placed = Carbon::now();
        $order->shipping_fee = 50.00;
        $order->status = 'Processing';
        $order->courier_id = $courierId;
        $order->save();
        return $order;
    }

    private function processOrderItems($order, $items) {
        foreach ($items as $cartItem) {
            $productId = $cartItem['id'];
            $quantity = $cartItem['quantity'];

            $product = Product::findOrFail($productId);
            $stock = Stock::where('product_id', $product->id)->firstOrFail();

            // if ($stock->quantity < $quantity) {
            //     throw new \Exception('Insufficient stock for product "' . $product->name . '"');
            // }

            if ($stock->quantity < $quantity) {
                $insufficientStockProducts[] = $product->name;
                continue;
            }

            $order->products()->attach($productId, [
                'quantity' => $quantity,
                'order_id' => $order->id,
            ]);

            $stock->quantity -= $quantity;
            $stock->save();
        }
    }

    private function createPayment($orderId, $paymentMethodId) {
        Payment::create([
            'order_id' => $orderId,
            'payment_method_id' => $paymentMethodId,
            'date_of_payment' => now(),
        ]);
    }
}
