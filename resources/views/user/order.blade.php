{{-- <link rel="stylesheet" href="{{ asset('css/order.css') }}">
@extends('layouts.master')
@section('title', 'Orders')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-3" style="padding-left: 0;">
            <div class="card fixed-sidebar" style="border: 2px solid black; top: 0; bottom:0; height: 100vh; overflow-y: auto; width: 100%;">
                <div class="card-body"style="overflow: hidden; overflow-y: scroll; -ms-overflow-style: none; scrollbar-width: none;">
                    @include('partials.sidebar')
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card mt-3 mb-3">
                <div class="card-body" id="orders">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection --}}

<link rel="stylesheet" href="{{ asset('css/order.css') }}">
@extends('layouts.master')
@section('title', 'Orders')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-3" style="padding-left: 0;">
            <div class="card fixed-sidebar" style="border: 2px solid black; top: 0; bottom:0; height: 100vh; overflow-y: auto; width: 100%;">
                <div class="card-body"style="overflow: hidden; overflow-y: scroll; -ms-overflow-style: none; scrollbar-width: none;">
                    @include('partials.sidebar')
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card mt-3 mb-3">
                <div class="card-body" id="orders">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

