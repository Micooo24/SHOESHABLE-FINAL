@extends('layouts.app')
@section('title', 'Analytics')

@section('content')
<div class="container mt-1 mr-2" style="width: 84%; max-width: 1200px;">

    <div class="card mt-3 mb-3">
        <div class="card-header mt-2 ml-5 mr-5 text-center" style="background-color: lightskyblue; border:2px solid black;">
            <h5 class="card-title">Analytics</h5>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card mt-3 mb-3">
                <div class="card-header text-center" style="background-color: #e0f7fa; border: 2px solid #00796b;">
                    <h5 class="card-title">Sales Chart</h5>
                </div>
                <div class="card-body" style="height: 60vh;">
                    <div class="chart-container" style="height: 100%;">
                        <canvas id="salesChart" style="width: 100%; height: 100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card mt-3 mb-3">
                <div class="card-header text-center" style="background-color: #e1bee7; border: 2px solid #ab47bc;">
                    <h5 class="card-title">Stock Chart</h5>
                </div>
                <div class="card-body" style="height: 60vh;">
                    <div class="chart-container" style="height: 100%;">
                        <canvas id="stockChart" style="width: 100%; height: 100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card mt-3 mb-3">
                <div class="card-header text-center" style="background-color: #ffeb3b; border: 2px solid #fbc02d;">
                    <h5 class="card-title">Product Chart</h5>
                </div>
                <div class="card-body" style="height: 60vh;">
                    <div class="chart-container" style="height: 100%;">
                        <canvas id="productChart" style="width: 100%; height: 100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card mt-3 mb-3">
                <div class="card-header text-center" style="background-color: #c8e6c9; border: 2px solid #2e7d32;">
                    <h5 class="card-title">Ratings Chart</h5>
                </div>
                <div class="card-body" style="height: 60vh;">
                    <div class="chart-container" style="height: 100%;">
                        <canvas id="ratingsChart" style="width: 100%; height: 100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
