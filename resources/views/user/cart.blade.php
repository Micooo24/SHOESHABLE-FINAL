{{-- @extends('layouts.master')
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
            <div class="card-header mt-3 ml-5 mr-5 text-center" style="background-color: lightskyblue; border:2px solid black;">
                <h5><img src="{{ asset('photo/logo.png') }}" alt="Shoessshable" class="title-image" style="width:45px; height:45px;"/>
                    <strong>Shoessshable</strong> | Shopping Cart</h5>
            </div>
            <div class="card mt-3 mb-3">
                <div class="card-body" style="border: 1px solid black;">
                    <table class="table table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Partial Total</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="customerCarts">
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card mt-3" style="border: 3px solid black;">
                <div class="card-body" style="background-color: #f8f9fa;">
                    <h4 class="card-title mb-4">Checkout Summary</h4>
                    <div class="mb-3">
                        <p>Total Cart Amount: <strong class="totalCartAmount" style="font-size: 1.5em;">₱0</strong></p>
                    </div>
                    <a id="checkoutButton" href="/checkout" class="btn btn-primary btn-lg ml-8" style="width: 60%; font-size: 1.2em; border:2px solid black; background-color:lightblue; color:black; display:none;">Proceed to Checkout</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection --}}


@extends('layouts.master')
@section('content')
<div class="container-fluid" style="border: 2px solid black; width: 100%; max-width: 2000px;">
    <div class="row">
        {{-- <div class="col-lg-3 mt-3">
            <div class="card fixed-sidebar" style="border: 2px solid black;">
                <div class="card-body">
                    @include('partials.sidebar')
                </div>
            </div>
        </div> --}}
        <div class="col-lg-3" style="padding-left: 0;">
            <div class="card fixed-sidebar" style="border: 2px solid black; top: 0; bottom:0; height: 100vh; overflow-y: auto; width: 100%;">
                <div class="card-body" style="overflow: hidden; overflow-y: scroll; -ms-overflow-style: none; scrollbar-width: none;">
                    @include('partials.sidebar')
                </div>
            </div>
        </div>
        <div class="col-lg-9 mb-12 mt-3" style="justify-content: space-between;">
            <div class="card" style="width:100%; border: 2px solid lightblue;">
                <div class="card-header mt-3 ml-5 mr-5 text-center" style="background-color: lightskyblue; border:2px solid black;">
                    <h5><img src="{{ asset('photo/logo.png') }}" alt="Shoessshable" class="title-image" style="width:45px; height:45px;"/>
                        <strong>SHOESSSHABLE</strong> | Shopping Cart</h5>
                </div>
                <div class="card mt-3 mb-3 ml-2 mr-2">
                    <div class="card-body" style=" border: 1px solid lightblue;">
                        <table class="table table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Partial Total</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="customerCarts">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card mt-3 mr-2 ml-2" style="border: 3px solid black;">
                    <div class="card-body" style="background-color: #f8f9fa;">
                        <h4 class="card-title mb-4">Cart Summary</h4>
                        <div class="mb-3">
                            <p>Total Cart Amount: <strong class="totalCartAmount" style="font-size: 1.5em;">₱0</strong></p>
                        </div>
                        <div class="d-flex justify-content-end" style="clear: both;">
                        <a id="checkoutButton" href="/checkout" class="btn btn-primary btn-lg ml-8" style="width: 30%; font-size: 1.2em; border:2px solid black; background-color:lightblue; color:black; display:none;">Proceed to Checkout</a>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
@endsection
