@extends('layouts.app')
@section('title', 'Profile')
@section('content')
<div id="flash-message" class="alert" style="display: none;"></div>
<div class="container mt-4 mr-2 text-center" style="width: 84%; max-width: 1200px;">
    <div class="card mt-3 mb-3">
        <div class="card-header mt-2 ml-5 mr-5 text-center" style="background-color: lightskyblue; border:2px solid black;">
            <h5 class="card-title">Your Profile</h5>
        </div>
        <div class="card-body">
            <div class="row justify-content-center">
                <div class="card-body">
                    <div class="text-center">
                        <img src="{{ Auth::user()->image ? asset(Auth::user()->image) : asset('photo/logo.png') }}" alt="Profile Image" style="width: 130px; height: 130px; border-radius: 50%;">
                    </div>
                    <hr>
                    <p><strong>Name:</strong> <span id="profile-name"></span></p>
                    <p><strong>Email:</strong> <span id="profile-email"></span></p>
                    <p><strong>Username:</strong> <span id="profile-username"></span></p>
                    <p><strong>Contact Number:</strong> <span id="profile-contact-number"></span></p>
                    <p><strong>Address:</strong> <span id="profile-address"></span></p>
                    <div class="text-center">
                        <button id="profileEdit" class="btn btn-primary btn-edit mr-2">Edit Profile</button>
                        <button id="userDeactivate" class="btn btn-deactivate">Deactivate</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true" style="--bs-modal-backdrop-bg: rgba(0,0,0,0.4);">
    <div class="modal-dialog" style="max-width: 800px; margin: 20px auto;">
        <div class="modal-content" style="border-radius: 15px; overflow: hidden; background: #ffffff; box-shadow: 0 15px 30px rgba(0,0,0,0.1); transform: scale(0.9); transition: transform 0.3s ease-in-out;">
            <div class="modal-header" style="background: linear-gradient(135deg, #6a11cb, #2575fc); color: #fff; border-bottom: 2px solid rgba(255,255,255,0.2); padding: 20px; position: relative;">
                <h5 class="modal-title" id="profileModalLabel" style="font-family: 'Roboto', sans-serif; font-size: 1.6rem; font-weight: 700;">Profile Edit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="background: #fff; border: none; border-radius: 50%; width: 35px; height: 35px; color: #333; font-size: 1.25rem; transition: background 0.3s, color 0.3s; box-shadow: 0 4px 8px rgba(0,0,0,0.2);">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modalBody" style="padding: 30px; font-family: 'Roboto', sans-serif; color: #333; line-height: 1.6; position: relative;">
            </div>
        </div>
    </div>
</div>
@endsection
