$(document).ready(function () {
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
                        switch (order.status) {
                            case 'Processing':
                                orderBorderClass = 'processing-order';
                                break;
                            case 'Shipped':
                                orderBorderClass = 'shipped-order';
                                break;
                            case 'Cancelled':
                                orderBorderClass = 'cancelled-order';
                                break;
                            case 'Delivered':
                                orderBorderClass = 'delivered-order';
                                break;
                        }
                        if (lastStatus && lastStatus !== order.status) {
                            ordersContainer.append('<div class="status-separator"></div>');
                        }

                        if (lastStatus !== order.status) {
                            ordersContainer.append(`<div class="status-header text-center">${order.status}</div>`);
                        }

                        const productCards = order.products.map(product => `
                            <div class="order-product mr-2">
                                <img src="${product.img_path.split(',')[0]}" alt="Product Image" style="object-fit: contain;">
                                <div class="details">
                                    <p><strong>${product.name}</strong></p>
                                    <p>${product.brand_name}</p>
                                </div>
                            </div>
                        `).join('');

                        const orderCard = `
                            <div class="card mt-3 ${orderBorderClass}">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6>Status: ${order.status}</h6>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="order-products">
                                        ${productCards}
                                    </div>
                                    <p><strong>Total Amount:</strong> ₱${order.total_amount.toFixed(2)}</p>
                                    ${order.status === 'Processing' ? `<button class="btn btn-danger cancel-order-btn" data-id="${order.id}">Cancel Order</button>` : ''}
                                </div>
                            </div>
                        `;

                        ordersContainer.append(orderCard);
                        lastStatus = order.status;
                    });
                } else {
                    ordersContainer.html('<p>No orders found.</p>');
                }
            },
            error: function (xhr, status, error) {
                $('#orders').html('<p>Error fetching orders.</p>');
            }
        });
    }

    function cancelOrder(orderId) {
        $.ajax({
            url: `/api/orders/${orderId}/cancel`,
            type: 'PUT',
            success: function (response) {
                fetchOrders(); // Refresh orders after cancellation
                alert('Order cancelled successfully.');
            },
            error: function (xhr, status, error) {
                alert('Error cancelling order.');
            }
        });
    }

    fetchOrders();

    $(document).on('click', '.cancel-order-btn', function () {
        const orderId = $(this).data('id');
        if (confirm('Are you sure you want to cancel this order?')) {
            cancelOrder(orderId);
        }
    });
});
