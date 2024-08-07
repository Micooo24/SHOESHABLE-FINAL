@extends('layouts.app')
@section('title', 'User Dashboard')
@section('content')
<div id="flash-message" class="alert" style="display: none;"></div>
<div class="container mt-1 mr-2" style="width: 84%; max-width: 1200px;">
        <div class="card mt-3 mb-3">
            <div class="card-header mt-2 ml-5 mr-5 mb-2 text-center" style="background-color: lightskyblue; border:2px solid black;" >
                <h5 class="card-title">User Management</h5>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card mt-3 mb-3">
                    <div class="card-body">
                        @include('layouts.flash-messages')
                    </div>
                    <div class="card-body">
                        <div class="table-responsive mt-3">
                            <table id="userTable" class="table table-hover">
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection
