@extends('layouts.app')
@section('title', 'Admin Dashboard')

@section('content')
<div class="container mt-1 mr-2" style="width: 84%; max-width: 1200px;">
    <div class="card mt-3 mb-3">
        <div class="card-header mt-2 ml-1 mr-1 text-center" style="background-color: lightskyblue; border:2px solid black;" >
            <h5 class="card-title">Administrator Dashboard</h5>
        </div>
        <div class="card-body">
            <h4 class="card-text text-center">Good Day! {{ Auth::user()->customer->username }} </h4>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-users"></i> Customers</h5>
                    <p class="card-text" id="totalCustomers">Loading...</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-shopping-cart"></i> Transactions</h5>
                    <p class="card-text" id="totalTransactions">Loading...</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-peso-sign"></i> Earnings</h5>
                    <p class="card-text" id="earnings">Loading...</p>
                </div>
            </div>
        </div>
    </div>
    <div class="card mt-3 mb-3">
        <div class="card-header mt-2 ml-5 mr-5 text-center" style="background-color: lightskyblue; border:2px solid black;" >
            <h5 class="card-title"><strong>Inventory Status</strong></h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-exclamation-triangle"></i> Low Stock</h5>
                            <div style="height: 250px;">
                                <canvas id="lowStockChart"></canvas>
                            </div>
                            <p class="mt-2">Count: <span id="lowStockCount">Loading...</span></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-ban"></i> Out of Stock</h5>
                            <div style="height: 250px;">
                                <canvas id="outOfStockChart"></canvas>
                            </div>
                            <p class="mt-2">Count: <span id="outOfStockCount">Loading...</span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
