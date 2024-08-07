
$(document).ready(function () {
    var csrfToken = $('meta[name="csrf-token"]').attr('content');

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': csrfToken
        }
    });

    function showSweetAlert(message, type) {
        const alertTypes = {
            success: 'success',
            error: 'error',
            warning: 'warning'
        };

        Swal.fire({
            icon: alertTypes[type],
            title: message,
            timer: 2000,
            showConfirmButton: false,
            timerProgressBar: true
        });
    }

    function fetchOrders() {
        $.ajax({
            url: '/api/myorder',
            type: 'GET',
            success: function (response) {
                const ordersContainer = $('#orders');
                ordersContainer.empty();

                if (response.orders.length > 0) {
                    // Sort orders by status
                    const sortedOrders = response.orders.sort((a, b) => {
                        const statusOrder = {
                            'Processing': 1,
                            'Shipped': 2,
                            'Cancelled': 3,
                            'Delivered': 4
                        };
                        return statusOrder[a.status] - statusOrder[b.status];
                    });

                    let lastStatus = '';

                    sortedOrders.forEach(order => {
                        let orderBorderClass = '';
                        let statusBadge = '';
                        switch (order.status) {
                            case 'Processing':
                                orderBorderClass = 'border-primary';
                                statusBadge = 'badge-primary';
                                break;
                            case 'Shipped':
                                orderBorderClass = 'border-info';
                                statusBadge = 'badge-info';
                                break;
                            case 'Cancelled':
                                orderBorderClass = 'border-danger';
                                statusBadge = 'badge-danger';
                                break;
                            case 'Delivered':
                                orderBorderClass = 'border-success';
                                statusBadge = 'badge-success';
                                break;
                        }
                        if (lastStatus && lastStatus !== order.status) {
                            ordersContainer.append('<div style="height: 1px; background-color: #e0e0e0; margin: 20px 0;"></div>');
                        }

                        if (lastStatus !== order.status) {
                            ordersContainer.append(`<div style="text-align: center; margin-top: 20px;"><h4 style="text-transform: uppercase;">${order.status}</h4></div>`);
                        }

                        const productCards = order.products.map(product => 
                            `<div style="display: flex; align-items: center; margin-bottom: 10px;">
                                <div>
                                    <h5><strong>${product.name}</strong></h5>
                                    <p>${product.brand_name}</p>
                                </div>
                            </div>`
                        ).join('');

                        const orderCard = 
                            `<div class="card mt-3 ${orderBorderClass}" style="margin-bottom: 20px;">
                                <div class="card-header" style="background-color: #f7f7f7;">
                                    <h6>Status: <span class="badge ${statusBadge}">${order.status}</span></h6>
                                </div>
                                <div class="card-body">
                                    <div style="margin-bottom: 20px;">
                                        ${productCards}
                                    </div>
                                    <p><strong>Total Amount:</strong> ₱${order.total_amount.toFixed(2)}</p>
                                    ${order.status === 'Processing' ? `<button class="btn btn-danger cancel-order-btn" data-id="${order.id}" style="margin-top: 10px;">Cancel Order</button>` : ''}
                                </div>
                            </div>`;

                        ordersContainer.append(orderCard);
                        lastStatus = order.status;
                    });
                } else {
                    ordersContainer.html('<p style="text-align: center;">No orders found.</p>');
                }
            },
            error: function (xhr, status, error) {
                $('#orders').html('<p style="text-align: center; color: red;">Error fetching orders.</p>');
            }
        });
    }

    function cancelOrder(orderId) {
        $.ajax({
            url: `/api/orders/${orderId}/cancel`,
            type: 'PUT',
            success: function (response) {
                fetchOrders(); // Refresh orders after cancellation
                showSweetAlert('Order cancelled successfully.', 'success');
            },
            error: function (xhr, status, error) {
                showSweetAlert('Error cancelling order.', 'error');
            }
        });
    }

    fetchOrders();

    $(document).on('click', '.cancel-order-btn', function () {
        const orderId = $(this).data('id');
        Swal.fire({
            title: 'Are you sure you want to cancel this order?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, cancel it!',
            cancelButtonText: 'No, keep it'
        }).then((result) => {
            if (result.isConfirmed) {
                cancelOrder(orderId);
            }
        });
    });
});