$(document).ready(function() {
    // Set up CSRF token for all AJAX requests
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': csrfToken
        }
    });

    function showFlashMessage(message, type) {
        var flashMessage = $('#flash-message');
        flashMessage.removeClass().addClass('alert alert-' + type).html(message).fadeIn();
        setTimeout(function() {
            flashMessage.fadeOut();
        }, 2000);
    }

    $('#loginForm').submit(function(event) {
        event.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            url: '/api/login',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                showFlashMessage(response.message);
                localStorage.setItem('token', response.token);
                console.log('Token:', response.token);
                window.location.href = response.redirect_url;
            },
            error: function(xhr) {
                showFlashMessage('Error: ' + xhr.responseJSON.message);
                console.log(xhr.responseJSON.errors);
            }
        });
    });

    // Handle register form submission
    $('#registerForm').submit(function(event) {
        event.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            url: '/api/register',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                showFlashMessage('User registered successfully!');
                $('#registerModal').hide();
                $('#loginModal').modal('show'); // Show the login modal
            },
            error: function(xhr) {
                showFlashMessage('Error: ' + xhr.responseJSON.message);
                console.log(xhr.responseJSON.errors);
            }
        });
    });

    // Show login modal when clicking on the login modal link
    $('a[href="#loginModal"]').on('click', function(e) {
        e.preventDefault();
        $('#loginModal').modal('show');
    });

    // Show register modal when clicking on the register modal link
    $('a[href="#registerModal"]').on('click', function(e) {
        e.preventDefault();
        $('#registerModal').modal('show');
    });

    // Handle logout button click
    $('#logoutButton').click(function(event) {
        event.preventDefault();
        $.ajax({
            url: '/api/logout',
            type: 'POST',
            data: {
                _token: csrfToken
            },
            success: function(response) {
                showFlashMessage('Logged out successfully!');
                localStorage.removeItem('token'); // Remove the token from localStorage
                window.location.href = '/';
                // $('body').html(response.view);
            },
            error: function(xhr) {
                showFlashMessage('Error: ' + xhr.responseText);
            }
        });
    });
});
