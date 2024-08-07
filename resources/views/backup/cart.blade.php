@extends('layouts.master')
@section('content')
<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-lg-3 mt-3">
            <div class="card fixed-sidebar">
                <div class="card-body">
                    @include('partials.sidebar')
                </div>
            </div>
        </div>
        <div class="col-md-9">

            <div class="card mt-3 mb-3">
                <div class="card-body">
                    <div id="flash-message" class="alert" style="display: none;"></div>
                    @if($carts->isEmpty())
                        <div class="text-center">
                            <h1>Your Cart is Empty</h1>
                            <p>Your cart is currently empty. Please add items to your cart to proceed.</p>
                        </div>
                    @else
                        <h1>Cart</h1>
                        <table class="table table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Product Name</th>
                                    <th>Description</th>
                                    <th>Cost</th>
                                    <th>Quantity</th>
                                    <th>Partial Total</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="customerCarts">
                                @php
                                    $cartTotal = 0;
                                @endphp
                                @foreach($carts as $cart)
                                    <tr class="cart-item">
                                        <td class="itemName">{{ $cart->name }}</td>
                                        <td class="itemDescription">{{ $cart->description }}</td>
                                        <td class="price">{{ $cart->cost }}</td>
                                        <td class="quantity">{{ $cart->pivot_cart_qty }}</td>
                                        <td class="partialTotal">{{ $partialTotal = $cart->cost * $cart->pivot_cart_qty }}</td>
                                        <td class="itemId" style="display: none;">{{ $cart->id }}</td>
                                        <td class="stockQuantity" style="display: none;">{{ $cart->total_stock }}</td>
                                        <td>
                                            <a href="javascript:void(0);" class="btn btn-outline-danger btn-sm minus-quantity" data-id="{{ $cart->id }}">
                                                <i class="fas fa-minus"></i>
                                            </a>
                                            <a href="javascript:void(0);" class="btn btn-outline-success btn-sm add-quantity" data-id="{{ $cart->id }}">
                                                <i class="fas fa-plus"></i>
                                            </a>
                                            <a href="javascript:void(0);" class="btn btn-outline-warning btn-sm remove-item" data-id="{{ $cart->id }}">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @php
                                        $cartTotal += $partialTotal;
                                    @endphp
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-body">
                        <p>Total Cart Amount: <strong>{{ $cartTotal }}</strong></p>
                        {{-- <h2>Select Courier</h2>
                        <div class="form-group">
                            <label for="courier">Choose a courier:</label>
                            <select name="courier_id" id="courier" class="form-control">
                                @foreach($couriers as $courier)
                                    <option value="{{ $courier->id }}">{{ $courier->courier_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="payment_method">Payment Method:</label>
                            <select name="payment_method" id="payment_method" class="form-control">
                                <option value="COD">Cash on Delivery</option>
                                <option value="E-Wallet">E-Wallet</option>
                                <option value="Online Banking">Online Banking</option>
                            </select>
                        </div>
                        <button class="btn btn-primary" id="checkout">Checkout</button> --}}
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

