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
        flashMessage.removeClass().addClass('alert alert-' + type).html(message).fadeIn();
        setTimeout(function() {
            flashMessage.fadeOut();
        }, 5000);
    }

    $('#orderTable').DataTable({
        ajax: {
            url: "/api/orders",
            dataSrc: "orders"
        },
        // dom: 'Bfrtip',
        columns: [
            { data: 'id', title: 'ID' },
            { data: 'customer.user.name', title: 'Name' },
            { data: 'customer.username', title: 'Username' },
            { data: 'status', title: 'Status' },
            { data: 'date_placed', title: 'Date Placed' },
            { data: 'date_delivered', title: 'Date Delivered' },
            { data: 'courier.courier_name', title: 'Courier Name' },
            {
                data: 'status',
                title: 'Action',
                render: function (data, type, row) {
                    var html = '';
                    if (data === 'Delivered') {
                        html = `<span class="badge badge-success">${data}</span>`;
                    } else if (data === 'Shipped') {
                        html = `<button class="btn btn-sm btn-success update-status" data-id="${row.id}" data-status="Delivered">Mark as Delivered</button>`;
                    } else if (data === 'Cancelled') {
                        html = `<span class="badge badge-secondary">${data}</span>`;
                    } else {
                        html = `<button class="btn btn-sm btn-primary update-status" data-id="${row.id}" data-status="Shipped">Mark as Shipped</button>`;
                    }
                    return html;
                }
            }
        ],
        drawCallback: function () {
            $('.update-status').off('click').on('click', function () {
                var orderId = $(this).data('id');
                var newStatus = $(this).data('status');
                updateOrderStatus(orderId, newStatus);
            });
        }
    });

    function updateOrderStatus(orderId, newStatus) {
        $.ajax({
            url: `/api/orders/${orderId}/update-status`,
            method: 'POST',
            data: {
                status: newStatus
            },
            success: function (response) {
                if (response.status === 'success') {
                    $('#orderTable').DataTable().ajax.reload();
                } else {
                     showFlashMessage('Failed to update order status: ' + response.message);
                }
            },
            error: function (xhr) {
                showFlashMessage('An error occurred while updating the order status: ' + xhr.responseJSON.message);
            }
        });
    }
});
