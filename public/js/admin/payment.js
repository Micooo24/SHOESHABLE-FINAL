$(document).ready(function () {
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    console.log('CSRF Token:', csrfToken);
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': csrfToken
        }
    });

    // Function to show flash messages
    function showFlashMessage(message, type) {
        var flashMessage = $('#flash-message');
        flashMessage.html(message); // Set the message content
        flashMessage.removeClass().addClass('alert'); // Remove any previous classes
        flashMessage.addClass('alert-' + type); // Add Bootstrap alert class
        flashMessage.addClass('show'); // Add 'show' class to display it
        flashMessage.fadeIn().delay(3000).fadeOut(); // Fade in and out after delay
    }

    // DataTable initialization
    var table = $('#paymentTable').DataTable({
        ajax: {
            url: "/api/payment-methods",
            dataSrc: ""
        },
        dom: 'Bfrtip',
        buttons: [
            {
                text: 'Add Payment Method',
                className: 'btn btn-primary btn-rounded btn-margin',
                action: function (e, dt, node, config) {
                    // Reset the form
                    $("#paymentform").trigger("reset");
                    // Remove validation messages
                    $('.error-message').remove();
                    // Show the modal
                    $('#paymentModal').modal('show');
                    // Hide the update button and show the submit button
                    $('#paymentUpdate').hide();
                    $('#paymentSubmit').show();
                }
            }
        ],
        columns: [
            { data: 'id', title: 'ID' },
            { data: 'payment_method', title: 'Payment Method' },
            {
                data: null,
                title: 'Actions',
                render: function (data, type, row) {
                    return `
                        <a href='#' class='editBtn' data-id="${data.id}" style="display: inline-block; padding: 10px 25px; background-color: #cce5ff; color: #004085; width: 110px; text-align: center; transition: background-color 0.3s ease;">
                            Edit
                        </a>
                        <a href='#' class='deleteBtn' data-id="${data.id}" style="display: inline-block; padding: 10px 25px; background-color: #f8d7da; color: #721c24; width: 110px; text-align: center; transition: background-color 0.3s ease;">
                            Delete
                        </a>`;
                }
            }
        ],
    });

    // Add Payment Method Submit
    $("#paymentSubmit").on('click', function (e) {
        e.preventDefault();
        if ($('#paymentform').valid()) {
            var data = new FormData($('#paymentform')[0]);
            $.ajax({
                type: "POST",
                url: "/api/payment-methods",
                data: data,
                contentType: false,
                processData: false,
                dataType: "json",
                success: function (data) {
                    console.log(data);
                    $("#paymentModal").modal("hide");
                    table.ajax.reload();
                    showFlashMessage("Payment method added successfully!", "success");
                },
                error: function (xhr, status, error) {
                    console.log(xhr.responseJSON);
                    showFlashMessage("Error adding payment method: " + xhr.responseJSON.message, "danger");
                }
            });
        }
    });

    // Edit Payment Method Button
    $('#paymentTable tbody').on('click', 'a.editBtn', function (e) {
        e.preventDefault();

        // Clear previous data and reset form
        $('#paymentform').trigger('reset');
        $('#paymentId').remove();

        var id = $(this).data('id');
        $('<input>').attr({ type: 'hidden', id: 'paymentId', name: 'id', value: id }).appendTo('#paymentform');
        $('#paymentModal').modal('show');
        $('#paymentSubmit').hide();
        $('#paymentUpdate').show();

        // Fetch payment method data
        $.ajax({
            type: 'GET',
            url: `/api/payment-methods/${id}`,
            dataType: 'json',
            success: function (data) {
                console.log(data);

                // Populate form fields
                $('#payment_name_id').val(data.payment_method);
            },
            error: function (xhr, status, error) {
                console.log(xhr.responseJSON);
                showFlashMessage("Error fetching payment method: " + xhr.responseJSON.message, "danger");
            }
        });
    });

    // Update Payment Method Submit
    $('#paymentUpdate').on('click', function (e) {
        e.preventDefault();
        if ($('#paymentform').valid()) {
            var id = $('#paymentId').val();
            var data = new FormData($('#paymentform')[0]);
            data.append("_method", "PUT");

            // Update payment method via AJAX
            $.ajax({
                type: 'POST',
                url: `/api/payment-methods/${id}`,
                data: data,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function (data) {
                    console.log(data);
                    $('#paymentModal').modal('hide');
                    table.ajax.reload();
                    showFlashMessage("Payment method updated successfully!", "success");
                },
                error: function (xhr, status, error) {
                    console.log(xhr.responseJSON);
                    showFlashMessage("Error updating payment method: " + xhr.responseJSON.message, "danger");
                }
            });
        }
    });

    // Delete Payment Method
    $('#paymentTable tbody').on('click', 'a.deleteBtn', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        var $row = $(this).closest('tr');
        bootbox.confirm({
            message: "Do you want to delete this payment method?",
            buttons: {
                confirm: {
                    label: 'Yes',
                    className: 'btn-success'
                },
                cancel: {
                    label: 'No',
                    className: 'btn-danger'
                }
            },
            callback: function (result) {
                if (result) {
                    $.ajax({
                        type: "DELETE",
                        url: `/api/payment-methods/${id}`,
                        dataType: "json",
                        success: function (data) {
                            console.log(data);
                            $row.fadeOut(4000, function () {
                                table.row($row).remove().draw();
                            });
                            showFlashMessage("Payment method deleted successfully!", "success");
                        },
                        error: function (xhr, status, error) {
                            console.log(xhr.responseJSON);
                            showFlashMessage("Error deleting payment method: " + xhr.responseJSON.message, "danger");
                        }
                    });
                }
            }
        });
    });

    // Form Validation
    $('#paymentform').validate({
        rules: {
            payment_method: {
                required: true,
                stringOnly: true
            }
        },
        messages: {
            payment_method: {
                required: "Please enter payment method"
            }
        },
        errorPlacement: function (error, element) {
            if (element.is(":radio") || element.is(":checkbox")) {
                error.appendTo(element.parent());
            } else {
                error.insertAfter(element);
            }
        }
    });

    // Custom validation methods
    $.validator.addMethod("stringOnly", function (value, element) {
        return this.optional(element) || /^[a-zA-Z\s]+$/.test(value);
    }, "Please enter only letters and spaces.");
});
