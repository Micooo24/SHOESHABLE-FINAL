@extends('layouts.master')
@section('content')
<div class="container-fluid" style="border: none; width: 100%; max-width: 2000px;">
    <div class="row">
        <div class="col-lg-3" style="padding-left: 0;">
            <div class="card fixed-sidebar" style="border: 2px solid black; top: 0; bottom:0; height: 100vh; overflow-y: auto; width: 100%;">
                <div class="card-body"style="overflow: hidden; overflow-y: scroll; -ms-overflow-style: none; scrollbar-width: none;">
                    @include('partials.sidebar')
                </div>
            </div>
        </div>
        <div class="col-lg-9 mb-12">
            <div class="card" style="border:transparent">
                <div class="card-body">
                    <div class="row" id="products">

                    <div class="row ml-2" id="search-results">
                    </div>
                    <div id="loading" class="modal" style="align-items: center; justify-content: center; display: flex; background-color: rgba(0,0,0,0.4); overflow: auto; height: 100%; width: 100%; top: 0; left: 0; z-index: 9999; position: fixed;">
                        <div class="modal-content" style="text-align: center; background-color: #fff; padding: 20px; width: 25%; border: 2px solid black;">
                            <div class="loading-text">Loading...</div>
                            <div class="spinkit">
                                <div class="sk-chase">
                                    <div class="sk-chase-dot"></div>
                                    <div class="sk-chase-dot"></div>
                                    <div class="sk-chase-dot"></div>
                                    <div class="sk-chase-dot"></div>
                                    <div class="sk-chase-dot"></div>
                                    <div class="sk-chase-dot"></div>
                                </div>
                            </div>
                            <div class="loading-line" style="animation: loading-line 2s infinite; border-radius: 2px; position: relative; margin: 10px auto; background: rgb(36, 182, 230); height: 7px; width: 60px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

