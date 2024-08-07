$(document).ready(function () {
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    // console.log('CSRF Token:', csrfToken);

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': csrfToken
        }
    });

    function showFlashMessage(message, type) {
        var flashMessage = $('#flash-message');
        flashMessage.removeClass();
        flashMessage.addClass('alert alert-' + type);
        flashMessage.html(message);
        flashMessage.fadeIn();

        // Automatically fade out after 5 seconds
        setTimeout(function() {
            flashMessage.fadeOut();
        }, 5000);
    }

    var table = $('#ctable').DataTable({
        ajax: {
            url: "/api/couriers",
            dataSrc: ""
        },
        dom: 'Bfrtip',
        buttons: [
            {
                text: 'Add Courier',
                className: 'btn btn-primary btn-rounded btn-margin',
                action: function (e, dt, node, config) {
                    $("#courierform").trigger("reset");
                    $('#courierModal').modal('show');
                    $('#courierUpdate').hide();
                    $('#courierSubmit').show();
                    $('#courierImages').remove();
                    $('#image').rules('add', {
                        required: true,
                        fileExtension: true,
                        messages: {
                            required: "Please upload an image",
                        }
                    });
                }
            },
            {
                text: 'Deleted Couriers',
                className: 'btn btn-primary btn-delete btn-margin',
                action: function (e, dt, node, config) {
                    $.ajax({
                        url: '/api/couriers/deleted',
                        method: 'POST',
                        success: function(response) {
                            console.log('Response:', response);
                            table.clear().rows.add(response).draw();
                        },
                        error: function(xhr, status, error) {
                            console.error('Error:', error);
                        }
                    });
                }
            },
        ],
        columns: [
            { data: 'id', title: 'ID' },
            { data: 'courier_name', title: 'Courier Name' },
            { data: 'contact_number', title: 'Contact Number' },
            { data: 'email', title: 'Email' },
            { data: 'service_area', title: 'Service Area' },
                {
                    data: 'img_path',
                    title: 'Image',
                    render: function (data, type, row) {
                        var imgPaths = data.split(',');
                        var carouselId = 'carousel-' + row.id;
                        var carouselIndicators = '';
                        var carouselInner = '';

                        imgPaths.forEach(function (path, index) {
                            var activeClass = index === 0 ? 'active' : '';
                            carouselIndicators += `<li data-target="#${carouselId}" data-slide-to="${index}" class="${activeClass}"></li>`;
                            carouselInner += `
                                <div class="carousel-item ${activeClass}">
                                    <img src="${path}" class="d-block w-100" style="height: 150px; width: 200px; object-fit: contain;">
                                </div>`;
                        });

                        var carouselHTML = `
                            <div id="${carouselId}" class="carousel slide" data-ride="carousel">
                                <ol class="carousel-indicators">
                                    ${carouselIndicators}
                                </ol>
                                <div class="carousel-inner">
                                    ${carouselInner}
                                </div>
                                <a class="carousel-control-prev" href="#${carouselId}" role="button" data-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Previous</span>
                                </a>
                                <a class="carousel-control-next" href="#${carouselId}" role="button" data-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Next</span>
                                </a>
                            </div>`;
                        return carouselHTML;
                    }
                },
            {
                data: null,
                title: 'Actions',
                render: function (data, type, row) {
                    if (row.deleted_at) {
                        return `<a href='#' class='restoreBtn' data-id="${data.id}" style="display: inline-block; padding: 5px 25px; background-color: #d4edda; color: #155724; width: 120px; text-align: center; transition: background-color 0.3s ease;">
                                    Restore
                                </a>`;
                    } else {
                        return `<a href='#' class='editBtn' data-id="${data.id}" style="display: inline-block; padding: 10px 25px; background-color: #cce5ff; color: #004085; width: 110px; text-align: center; transition: background-color 0.3s ease;">
                                    Edit
                                </a>
                                <a href='#' class='deleteBtn' data-id="${data.id}" style="display: inline-block; padding: 10px 25px; background-color: #f8d7da; color: #721c24; width: 110px; text-align: center; transition: background-color 0.3s ease;">
                                    Delete
                                </a>`;
                    }
                }
            }
        ],
    });

    $('#refreshButton').on('click', function() {
        table.ajax.reload();
    });

    // Add Courier
    $("#courierSubmit").on('click', function (e) {
        e.preventDefault();
        if ($('#courierform').valid()) {
            var data = $('#courierform')[0];
            let formData = new FormData(data);
            $.ajax({
                type: "POST",
                url: "/api/couriers",
                data: formData,
                contentType: false,
                processData: false,
                dataType: "json",
                success: function (data) {
                    console.log(data);
                    $("#courierModal").modal("hide");
                    table.ajax.reload();
                    showFlashMessage("Courier added successfully!", "success");
                },
                error: function (error) {
                    console.log(error);
                }
            });
        }
    });

    // Edit Courier
    $('#ctable tbody').on('click', 'a.editBtn', function (e) {
        e.preventDefault();
        $('#courierImages').remove();
        $('#courierId').remove();
        $("#courierform").trigger("reset");

        var id = $(this).data('id');
        $('<input>').attr({ type: 'hidden', id: 'courierId', name: 'id', value: id }).appendTo('#courierform');
        $('#courierModal').modal('show');
        $('#courierSubmit').hide();
        $('#courierUpdate').show();

        $.ajax({
            type: "GET",
            url: `/api/couriers/${id}`,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            dataType: "json",
            success: function (data) {
                $('#courier_name').val(data.courier_name);
                $('#email_id').val(data.email);
                $('#contact_number').val(data.contact_number);
                $('#service_area').val(data.service_area);

                var imagesHTML = '';
                data.img_path.split(',').forEach(function (path) {
                    if (path.endsWith('.jpg') || path.endsWith('.jpeg') || path.endsWith('.png')) {
                        imagesHTML += `<img src="${path}" width='200px' height='200px'>`;
                    }
                });
                $("#courierform").append("<div id='courierImages'>" + imagesHTML + "</div>");
                $('#image').rules('remove', 'required');
            },
            error: function (error) {
                console.log(error);
            }
        });
    });

    // Update Courier
    $("#courierUpdate").on('click', function (e) {
        e.preventDefault();
        if ($('#courierform').valid()) {
            var id = $('#courierId').val();
            var data = $('#courierform')[0];
            let formData = new FormData(data);
            formData.append("_method", "PUT");
            $.ajax({
                type: "POST",
                url: `/api/couriers/${id}`,
                data: formData,
                contentType: false,
                processData: false,
                dataType: "json",
                success: function (data) {
                    console.log(data);
                    $('#courierModal').modal("hide");
                    table.ajax.reload();
                    showFlashMessage("Courier updated successfully!", "success");
                },
                error: function (error) {
                    console.log(error);
                }
            });
        }
    });

    // Delete Courier
    $('#ctable tbody').on('click', 'a.deleteBtn', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        var $row = $(this).closest('tr');

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "DELETE",
                    url: `/api/couriers/${id}`,
                    dataType: "json",
                    success: function (data) {
                        console.log(data);
                        $row.fadeOut(4000, function () {
                            table.row($row).remove().draw();
                        });
                        Swal.fire(
                            'Deleted!',
                            'Courier has been deleted.',
                            'success'
                        );
                    },
                    error: function (error) {
                        console.log(error);
                        Swal.fire(
                            'Error!',
                            'There was a problem deleting the courier.',
                            'error'
                        );
                    }
                });
            }
        });
    });

    // Restore Courier
    $('#ctable tbody').on('click', 'a.restoreBtn', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        var $row = $(this).closest('tr');
    
        Swal.fire({
            title: 'Do you want to restore this courier?',
            text: "This action will restore the courier!",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, restore it!',
            cancelButtonText: 'No, cancel!',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    url: `/api/courier/restore/${id}`,
                    dataType: "json",
                    success: function (data) {
                        console.log(data);
                        $row.fadeOut(2000, function () {
                            table.row($row).remove().draw();
                        });
                        Swal.fire(
                            'Restored!',
                            'Courier has been restored.',
                            'success'
                        );
                    },
                    error: function (error) {
                        console.log(error);
                    }
                });
            }
        });
    });


    $('#import-form-courier').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            url: '/api/import-couriers',
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                showFlashMessage(response.message);
                table.ajax.reload();
            },
            error: function(xhr) {
                var errorMsg = 'An error occurred';
                if (xhr.responseJSON && xhr.responseJSON.error) {
                    errorMsg = xhr.responseJSON.error;
                }
                showFlashMessage(errorMsg);
            }
        });
    });





    // Form Validation
    $.validator.addMethod("stringOnly", function (value, element) {
        return this.optional(element) || /^[a-zA-Z\s]+$/.test(value);
    }, "Please enter only letters and spaces.");
    $.validator.addMethod("fileExtension", function(value, element) {
        return this.optional(element) || /\.(jpg|jpeg|png)$/i.test(value);
    }, "Please select a valid file type (jpg, jpeg, png)");

    // Initialize validation on the form
    $('#courierform').validate({
        rules: {
            courier_name: {
                required: true,
                stringOnly: true
            },
            contact_number: {
                required: true,
                digits: true
            },
            email: {
                required: true,
                email: true
            },
            service_area: {
                required: true
            },
            'uploads[]': {
                // required: true,
                fileExtension: true // Apply the custom validation method for file extensions
            }
        },
        messages: {
            courier_name: {
                required: "Please enter the courier name",
                stringOnly: "Name can only contain letters and spaces" // Custom message for the stringOnly validation
            },
            contact_number: {
                required: "Please enter a contact number",
                digits: "Please enter a valid contact number"
            },
            email: {
                required: "Please enter an email address",
                email: "Please enter a valid email address"
            },
            service_area: {
                required: "Please enter a valid service area"
            },
            'uploads[]': {
                // required: "Please select an image file",
                fileExtension: "Please upload files with jpg, jpeg, or png extensions only"
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
});
