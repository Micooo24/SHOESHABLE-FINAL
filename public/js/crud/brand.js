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

        setTimeout(function() {
            flashMessage.fadeOut();
        }, 5000);
    }

    var table = $('#brandtable').DataTable({
        ajax: {
            url: "/api/brands",
            dataSrc: ""
        },
        dom: 'Bfrtip',
        buttons: [
            {
                text: 'Add Brand',
                className: 'btn btn-primary btn-rounded btn-margin',
                action: function (e, dt, node, config) {
                    $("#brandform").trigger("reset");
                    $('#brandModal').modal('show');
                    $('#brandUpdate').hide();
                    $('#brandSubmit').show();
                    $('#brandImages').remove();
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
                text: 'Deleted Brands',
                className: 'btn btn-primary btn-delete btn-margin',
                action: function (e, dt, node, config) {
                    $.ajax({
                        url: '/api/brands/deleted',
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
            { data: 'brand_name', title: 'Brand Name' },
            { data: 'description', title: 'Description' },
            {
                data: 'logo',
                title: 'Image',
                render: function (data, type, row) {
                    var imgPaths = data.split(',');
                    var carouselId = `carousel_${row.id}`; // Unique ID for each carousel
                    var carouselHTML = `<div id="${carouselId}" class="carousel slide" data-ride="carousel">`;
                    carouselHTML += '<ol class="carousel-indicators">';
                    imgPaths.forEach(function (path, index) {
                        carouselHTML += `<li data-target="#${carouselId}" data-slide-to="${index}" class="${index === 0 ? 'active' : ''}"></li>`;
                    });
                    carouselHTML += '</ol>';
                    carouselHTML += '<div class="carousel-inner">';
                    imgPaths.forEach(function (path, index) {
                        if (path.endsWith('.jpg') || path.endsWith('.jpeg') || path.endsWith('.png')) {
                            carouselHTML += `<div class="carousel-item ${index === 0 ? 'active' : ''}">
                                              <img src="${path}" class="d-block w-100" style="height: 150px; width: 200px; object-fit: contain;">
                                            </div>`;
                        }
                    });
                    carouselHTML += '</div>';
                    carouselHTML += `<a class="carousel-control-prev" href="#${carouselId}" role="button" data-slide="prev">`;
                    carouselHTML += '<span class="carousel-control-prev-icon" aria-hidden="true"></span>';
                    carouselHTML += '<span class="sr-only">Previous</span>';
                    carouselHTML += '</a>';
                    carouselHTML += `<a class="carousel-control-next" href="#${carouselId}" role="button" data-slide="next">`;
                    carouselHTML += '<span class="carousel-control-next-icon" aria-hidden="true"></span>';
                    carouselHTML += '<span class="sr-only">Next</span>';
                    carouselHTML += '</a>';
                    carouselHTML += '</div>';
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


    // Add Brand
    $("#brandSubmit").on('click', function (e) {
        e.preventDefault();
        if ($('#brandform').valid()) {
            var data = $('#brandform')[0];
            let formData = new FormData(data);
            $.ajax({
                type: "POST",
                url: "/api/brands",
                data: formData,
                contentType: false,
                processData: false,
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                dataType: "json",
                success: function (data) {
                    console.log(data);
                    $("#brandModal").modal("hide");
                    $('#brandtable').DataTable().ajax.reload();
                    showFlashMessage("Brand added successfully!", "success");
                },
                error: function (error) {
                    console.log(error);
                }
            });
        }
    });

    // Edit Brand
    $('#brandtable tbody').on('click', 'a.editBtn', function (e) {
        e.preventDefault();
        $('#brandImages').remove();
        $('#brandId').remove();
        $("#brandform").trigger("reset");

        var id = $(this).data('id');
        $('<input>').attr({ type: 'hidden', id: 'brandId', name: 'id', value: id }).appendTo('#brandform');
        $('#brandModal').modal('show');
        $('#brandSubmit').hide();
        $('#brandUpdate').show();

        $.ajax({
            type: "GET",
            url: `/api/brands/${id}`,
            dataType: "json",
            success: function (data) {
                $('#brand_id').val(data.brand_name);
                $('#description_id').val(data.description);

                var imagesHTML = '';
                data.logo.split(',').forEach(function (path) {
                    if (path.endsWith('.jpg') || path.endsWith('.jpeg') || path.endsWith('.png')) {
                        imagesHTML += `<img src="${path}" width='200px' height='200px'>`;
                    }
                });
                $("#brandform").append("<div id='brandImages'>" + imagesHTML + "</div>");
                $('#image').rules('remove', 'required');
            },
            error: function (error) {
                console.log(error);
            }
        });
    });

    // Update Brand
    $("#brandUpdate").on('click', function (e) {
        e.preventDefault();
        if ($('#brandform').valid()) {
            var id = $('#brandId').val();
            var data = $('#brandform')[0];
            let formData = new FormData(data);
            formData.append("_method", "PUT");
            $.ajax({
                type: "POST",
                url: `/api/brands/${id}`,
                data: formData,
                contentType: false,
                processData: false,
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                dataType: "json",
                success: function (data) {
                    console.log(data);
                    $('#brandModal').modal("hide");
                    $('#brandtable').DataTable().ajax.reload();
                    showFlashMessage("Brand updated successfully!", "success");
                },
                error: function (error) {
                    console.log(error);
                }
            });
        }
    });

    // Delete Brand
    $('#brandtable tbody').on('click', 'a.deleteBtn', function (e) {
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
            cancelButtonText: 'No, cancel!',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "DELETE",
                    url: `/api/brands/${id}`,
                    dataType: "json",
                    success: function (data) {
                        console.log(data);
                        $row.fadeOut(2000, function () {
                            table.row($row).remove().draw();
                        });
                        Swal.fire(
                            'Deleted!',
                            'Brand has been deleted.',
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

    $('#brandtable tbody').on('click', 'a.restoreBtn', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        var $row = $(this).closest('tr');
    
        Swal.fire({
            title: 'Do you want to restore this brand?',
            text: "This action will restore the brand!",
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
                    url: `/api/brand/restore/${id}`,
                    dataType: "json",
                    success: function (data) {
                        console.log(data);
                        $row.fadeOut(2000, function () {
                            table.row($row).remove().draw();
                        });
                        Swal.fire(
                            'Restored!',
                            'Brand has been restored.',
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

    $('#import-form-brand').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            url: '/api/import-brands',
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                $('#brandtable').DataTable().ajax.reload();
                showFlashMessage(response.message);
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


    $.validator.addMethod("stringOnly", function (value, element) {
        return this.optional(element) || /^[a-zA-Z\s]+$/.test(value);
    }, "Please enter only letters and spaces.");

    $.validator.addMethod("fileExtension", function (value, element) {
        return this.optional(element) || /\.(jpg|jpeg|png)$/i.test(value);
    }, "Please select a valid file type (jpg, jpeg, png)");

    $('#brandform').validate({
        rules: {
            brand_name: {
                required: true,
                stringOnly: true
            },
            description: {
                required: true,
            },
            'uploads[]': {
                // required: true,
                fileExtension: true
            }
        },
        messages: {
            brand_name: {
                required: "Please enter brand name"
            },
            description: {
                required: "Please enter valid description",
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

