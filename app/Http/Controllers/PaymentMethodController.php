<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PaymentMethod;

class PaymentMethodController extends Controller
{
    public function index()
    {
        $paymentMethods = PaymentMethod::all();
        return response()->json($paymentMethods);
    }

    public function show(string $id)
   {
        $paymentMethods = PaymentMethod::where('id', $id)->first();
        return response()->json($paymentMethods);
   }

    public function store(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|string|max:255',
        ]);

        $paymentMethod = PaymentMethod::create([
            'payment_method' => $request->payment_method,
        ]);

        return response()->json($paymentMethod, 201);
    }

        public function update(Request $request, $id)
    {
        $request->validate([
            'payment_method' => 'required|string|max:255',
        ]);

        $paymentMethod = PaymentMethod::findOrFail($id);
        $paymentMethod->update([
            'payment_method' => $request->payment_method,
        ]);

        return response()->json($paymentMethod);
    }
    public function destroy($id)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);
        $paymentMethod->delete();

        return response()->json(['message' => 'Payment method deleted successfully']);
    }
}
