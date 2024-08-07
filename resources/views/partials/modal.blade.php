<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<!-- Login Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div id="flash-message" class="alert" style="display: none;"></div>
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="container text-center logo-title-container">
                    <img src="{{ asset('photo/logo.png') }}" alt="Shoessshable Logo" height="100" width="100">
                    {{-- <h2>Shoessshable</h2> --}}
                    <h5 class="mt-3">Login Form</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            <div class="modal-body">
                <form id="loginForm">
                    @csrf <!-- Include CSRF token -->
                    <div class="input-group">
                        <label for="loginEmail" class="input-group_label">Email</label>
                        <input type="email" class="input-group_input" id="loginEmail" name="email">
                    </div>
                    <div class="input-group">
                        <label for="loginPassword" class= "input-group_label">Password</label>
                        <input type="password" class="input-group_input" id="loginPassword" name="password">
                    </div>
                    <button type="submit" class="btn btn-primary btn-login">Login</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Registration Modal -->
<div class="modal fade" id="registerModal" tabindex="-1" role="dialog" aria-labelledby="registerModalLabel" aria-hidden="true">
    <div id="flash-message" class="alert" style="display: none;"> </div>
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="container text-center logo-title-container">
                    <img src="{{ asset('photo/logo.png') }}" alt="Shoessshable Logo" height="100" width="100">
                    {{-- <h2>Shoessshable</h2> --}}
                    <h5 class="mt-3">Register Form</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            <div class="modal-body">
                <form id="registerForm" method="POST" enctype="multipart/form-data">
                    @csrf <!-- CSRF token -->
                    <div class="input-group">
                        <label for="registerName"class="input-group_label">Name</label>
                        <input type="text" class="input-group_input" id="registerName" name="name">
                    </div>
                    <div class="input-group">
                        <label for="registerUsername"class="input-group_label">Username</label>
                        <input type="text" class="input-group_input" id="registerUsername" name="username">
                    </div>
                    <div class="input-group">
                        <label for="registerEmail"class="input-group_label">Email</label>
                        <input type="email" class="input-group_input" id="registerEmail" name="email">
                    </div>
                    <div class="input-group">
                        <label for="registerAddress"class="input-group_label">Address</label>
                        <input type="text" class="input-group_input" id="registerAddress" name="address">
                    </div>
                    <div class="input-group">
                        <label for="registerContactNumber"class="input-group_label">Contact Number</label>
                        <input type="text" class="input-group_input" id="registerContactNumber" name="contact_number">
                    </div>
                    <div class="input-group">
                        <label for="registerPassword"class="input-group_label">Password</label>
                        <input type="password" class="input-group_input" id="registerPassword" name="password">
                    </div>
                    <div class="input-group">
                        <label for="registerPassword_confirmation"class="input-group_label">Confirm Password</label>
                        <input type="password" class="input-group_input" id="registerPassword_confirmation" name="password_confirmation" >
                    </div>
                    <div class="input-group">
                        <label for="registerImage"class="input-group_label">Profile Image</label>
                        <input type="file" class="input-group_input" id="registerImage" name="image" >
                    </div>
                    <button type="submit" class="btn btn-primary btn-login">Register</button>
                </form>
            </div>
        </div>
    </div>

</div>
