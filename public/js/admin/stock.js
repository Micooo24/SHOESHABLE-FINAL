$(document).ready(function() {

    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    // console.log('CSRF Token:', csrfToken);
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': csrfToken
        }
    });
    function showFlashMessage(message, type) {
        var flashMessage = $('#flash-message');
        flashMessage.html(message);
        flashMessage.removeClass().addClass('alert');
        flashMessage.addClass('alert-' + type);
        flashMessage.addClass('show');
        flashMessage.fadeIn().delay(3000).fadeOut();
    }

    // DataTable initialization
    var table = $('#stockTable').DataTable({
        "lengthChange": false,
        ajax: {
            url: `/api/stocks`, // Replace with your actual URL
            type: "GET",
            dataSrc: function (json) {
                // Ensure the data structure is correctly mapped to the DataTable's expected format
                return json.stocks.map(function(stock) {
                    return {
                        product_id: stock.product_id,
                        product_name: stock.product.name,
                        brand_name: stock.product.brand.brand_name,
                        supplier_name: stock.product.supplier.name,
                        quantity: stock.quantity
                    };
                });
            }
        },
        // dom: 'Bfrtip',
        columns: [
            { data: 'product_id', title: 'ID' },
            { data: 'product_name', title: 'Product Name' },
            { data: 'brand_name', title: 'Brand Name' },
            { data: 'supplier_name', title: 'Supplier Name' },
            { data: 'quantity', title: 'Stocks' },
            {
                data: null, title: 'Actions',
                render: function (data, type, row) {
                    return `<a href='#' class='editBtn' id='editbtn' data-id="${data.product_id}"><i class='fas fa-edit' aria-hidden='true' style='font-size:24px'></i></a>`;
                }
            }
        ]
    });

    // Edit Stock Button
    $('#stockTable tbody').on('click', 'a.editBtn', function (e) {
        e.preventDefault();
        $('#stockId').remove();
        $("#stockform").trigger("reset");

        var id = $(this).data('id');
        var rowData = table.rows().data().toArray().find(row => row.product_id === id);

        $('<input>').attr({ type: 'hidden', id: 'stockId', name: 'id', value: id }).appendTo('#stockform');
        $('#stockModal').modal('show');
        $('#stockSubmit').hide();
        $('#stockUpdate').show();

        // Populate modal fields with current row data
        $('#prod_name_id').val(rowData.product_name);
        $('#prod_name_id').prop('disabled', true);
        $('#brand_name_id').val(rowData.brand_name);
        $('#brand_name_id').prop('disabled', true);
        $('#supplier_name_id').val(rowData.supplier_name);
        $('#supplier_name_id').prop('disabled', true);
        $('#quantity_id').val(rowData.quantity); // Populate quantity field
    });

    // Update Stock Submit
    $("#stockUpdate").on('click', function (e) {
        e.preventDefault();
        if ($('#stockform').valid()) {
            var id = $('#stockId').val();
            var data = new FormData($('#stockform')[0]);
            data.append("_method", "PUT");

            $.ajax({
                type: "POST",
                url: `/api/stocks/${id}`, // Use relative URL
                data: data,
                contentType: false,
                processData: false,
                headers: { 'X-CSRF-TOKEN': csrfToken },
                dataType: "json",
                success: function (data) {
                    console.log(data);
                    $('#stockModal').modal("hide");
                    $('#stockTable').DataTable().ajax.reload(); // Use correct table ID
                    showFlashMessage("Stock updated successfully!", "success");
                },
                error: function (error) {
                    console.log(error);
                }
            });
        }
    });

    $.validator.addMethod("stocks", function (value, element) {
        return this.optional(element) || (value > -1);
    }, "Please enter a valid quantity");

    $('#stockform').validate({
        rules: {
            quantity: {
                required: true,
                digits: true, // Apply the custom validation method for integers
                stocks: true,  // Apply the custom validation method for greater than zero
            }
        },
        messages: {
            quantity: {
                required: "Please enter valid quantity",
                digits: "Quantity must be an integer",
                stocks: "Please enter a valid quantity",
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
