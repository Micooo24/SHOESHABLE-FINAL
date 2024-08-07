@extends('layouts.master')
@section('content')
<div class="container-fluid" style="border: 2px solid black; width: 100%; max-width: 2000px;">
    <div class="row">
        {{-- <div class="col-lg-3">
            <div class="card fixed-sidebar mt-3" style="border: 2px solid black;">
                <div class="card-body p-4">
                    @include('partials.sidebar')
                </div>
            </div>
        </div> --}}
        <div class="col-lg-3" style="padding-left: 0;">
            <div class="card fixed-sidebar" style="border: 2px solid black; top: 0; bottom:0; height: 100vh; overflow-y: auto; width: 100%;">
                <div class="card-body"style="overflow: hidden; overflow-y: scroll; -ms-overflow-style: none; scrollbar-width: none;">
                    @include('partials.sidebar')
                </div>
            </div>
        </div>
        <div class="col-lg-9 mb-4">
            <div class="card mt-3" style="border: 2px solid black; background: linear-gradient(white, #FFEEF1);">
                <div class="card-body p-4">
                    <div class="wishlist-header text-center" style="font-family: 'Quick Sand', 'sans-serif'; font-size: 3rem; color: black;">
                        <i class="fa fa-heart-o" aria-hidden="true"></i>
                        <h2>MY WISHLIST</h2>
                    </div>
                    <div class="container mt-5">
                        <div id="wishlist-container"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
