$(document).ready(function () {

    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    console.log('CSRF Token:', csrfToken);
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

    $('#multiple-importForm').on('submit', function (e) {
        e.preventDefault();

        let formData = new FormData(this);

        $.ajax({
            url: '/api/import-multiple-sheets',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                $('#adminProduct').DataTable().ajax.reload();
                $('#adminSupplier').DataTable().ajax.reload();
                $('#adminCourier').DataTable().ajax.reload();
                $('#adminBrand').DataTable().ajax.reload();
                showFlashMessage(response.message);
            },
            error: function (response) {
                showFlashMessage('Error impoting multiple sheets', 'danger');
            }
        });
    });

    $('#adminBrand').DataTable({
        "lengthChange": false,
        ajax: {
            url: "/api/brands",
            dataSrc: ""
        },
        columns: [
            { data: 'id', title: 'ID' },
            { data: 'brand_name', title: 'Brand Name' },
            { data: 'description', title: 'Description' },
            {
                data: 'logo',
                title: 'Image',
                render: function (data, type, row) {
                    var imgPaths = data.split(',');
                    var carouselId = `carousel_${row.id}`;
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
        ],
    });

    $('#adminCourier').DataTable({
        "lengthChange": false,
        ajax: {
            url: "/api/couriers",
            dataSrc: ""
        },
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
            ],
        });

        $('#adminProduct').DataTable({
            "lengthChange": false,
            ajax: {
                url: "/api/products",
                dataSrc: "products"
            },
            columns: [
                { data: 'id', title: 'ID' },
                {
                    data: 'img_path',title: 'Product Image',
                    render: function (data, type, row) {
                        var imgPaths = data.split(',');
                        var carouselId = `carousel_${row.id}`;
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
                { data: 'name', title: 'Product Name' },
                {
                    data: 'brand', title: 'Brand Name',
                    render: function (data) {
                        return data ? data.brand_name : 'No Brand';
                    }
                },
                {
                    data: 'supplier', title: 'Supplier Name',
                    render: function (data) {
                        return data ? data.name : 'No Supplier';
                    }
                },
                { data: 'description', title: 'Description' },
                { data: 'cost', title: 'Cost' },
                {
                    data: 'stocks.quantity',title: 'Stocks',
                    render: function(data, type, row) {
                        return data ? data : '0';
                    }
                },
            ]
        });

        $('#adminSupplier').DataTable({
            "lengthChange": false,
            ajax: {
                url: "/api/suppliers",
                dataSrc: ""
            },
            columns: [
                { data: 'id', title: 'ID' },
                {
                    data: null, title: 'Image',
                    render: function (data, type, row) {
                        var imgPaths = data.img_path.split(',');
                        var carouselId = `carousel_${row.id}`; // Generate a unique ID for the carousel
                        var carouselHTML = `<div id="${carouselId}" class="carousel slide" data-ride="carousel" style="width: 200px;">`;
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
                        carouselHTML += `<a class="carousel-control-prev" href="#${carouselId}" role="button" data-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span class="sr-only">Previous</span>
                                         </a>`;
                        carouselHTML += `<a class="carousel-control-next" href="#${carouselId}" role="button" data-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span class="sr-only">Next</span>
                                         </a>`;
                        carouselHTML += '</div>';
                        return carouselHTML;
                    }
                },
                { data: 'name', title: 'Supplier Name' },
                { data: 'email', title: 'Email' },
                { data: 'contact_number', title: 'Contact' },
            ],
        });
});
