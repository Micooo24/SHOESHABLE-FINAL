$(document).ready(function () {
    var csrfToken = $('meta[name="csrf-token"]').attr('content');

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': csrfToken
        }
    });

    function showAlert(message, type) {
        const alertTypeClass = {
            success: 'custom-bootbox-success',
            error: 'custom-bootbox-error',
            warning: 'custom-bootbox-warning'
        };

        const alertIcon = {
            success: '<i class="fas fa-check-circle"></i>',
            error: '<i class="fas fa-exclamation-circle"></i>',
            warning: '<i class="fas fa-exclamation-triangle"></i>'
        };

        const dialog = bootbox.dialog({
            message: `<div class="custom-bootbox-content ${alertTypeClass[type]}">
                        ${alertIcon[type]} <span>${message}</span>
                      </div>`,
            backdrop: true,
            closeButton: false,
            onShown: function () {
                setTimeout(function() {
                    dialog.modal('hide');
                }, 1000);
            }
        });

        dialog.on('shown.bs.modal', function() {
            setTimeout(function() {
                dialog.find('.custom-bootbox-content').fadeOut(2000, function() {
                    dialog.modal('hide');
                });
            }, 2000);
        });
    }

    $("#products").on('click', '.add', function () {
        var item = $(this).closest('.item');
        var productId = item.find('.itemId').text();
        var quantity = item.find('.qty').val();

        $.ajax({
            type: "POST",
            url: "/api/add-to-cart",
            data: JSON.stringify({
                product_id: productId,
                cart_qty: quantity
            }),
            contentType: "application/json",
            success: function (response) {
                showAlert(response.message, 'success');
                fetchCarts(); // Refresh cart items after adding to cart
            },
            error: function (xhr) {
                var response = JSON.parse(xhr.responseText);
                showAlert(response.error || 'An error occurred', 'danger');
            }
        });
    });

    $("#wishlist-container").on('click', '.add', function () {
        var productId = $(this).data('product-id');
        var quantity = 1;

        $.ajax({
            type: "POST",
            url: "/api/add-to-cart",
            data: JSON.stringify({
                product_id: productId,
                cart_qty: quantity
            }),
            contentType: "application/json",
            success: function (response) {
                showAlert(response.message, 'success');
            },
            error: function (xhr) {
                var response = JSON.parse(xhr.responseText);
                showAlert(response.error || 'An error occurred', 'danger');
            }
        });
    });

    $(document).on('click', '.fa-plus', function () {
        var item = $(this).closest('.cart-item');
        var productId = item.find('.itemName').data('id');
        var quantity = parseInt(item.find('.quantity-value').text());
        var stockQuantity = parseInt(item.find('.stockQuantity').text());

        if (quantity + 1 > stockQuantity) {
            showAlert('Not enough stock available', 'warning');
            return;
        }

        quantity++;

        $.ajax({
            type: "POST",
            url: "/api/update-cart",
            data: JSON.stringify({
                product_id: productId,
                cart_qty: quantity
            }),
            contentType: "application/json",
            success: function (response) {
                showAlert(response.message, 'success');
                var price = parseFloat(item.data('price'));
                item.find('.quantity-value').text(quantity);
                item.find('.partialTotal').text(formatCurrency(quantity * price));
                updateCartTotal();
            },
            error: function (xhr) {
                var response = JSON.parse(xhr.responseText);
                if (xhr.status === 400 && response.message === 'Not enough stock available') {
                    showAlert('Not enough stock available', 'warning');
                } else {
                    showAlert('Error updating cart.', 'danger');
                }
            }
        });
    });

    $(document).on('click', '.fa-minus', function () {
        var item = $(this).closest('.cart-item');
        var productId = item.find('.itemName').data('id');
        var quantity = parseInt(item.find('.quantity-value').text());

        if (quantity > 1) {
            quantity--;

            $.ajax({
                type: "POST",
                url: "/api/update-cart",
                data: JSON.stringify({
                    product_id: productId,
                    cart_qty: quantity
                }),
                contentType: "application/json",
                success: function (response) {
                    showAlert(response.message, 'success');
                    var price = parseFloat(item.data('price'));
                    item.find('.quantity-value').text(quantity);
                    item.find('.partialTotal').text(formatCurrency(quantity * price));
                    updateCartTotal();
                },
                error: function () {
                    showAlert('Error updating cart.', 'danger');
                }
            });
        }
    });

    $(document).on('click', '.fa-trash', function () {
        var item = $(this).closest('.cart-item');
        var productId = item.find('.itemName').data('id');

        $.ajax({
            type: "POST",
            url: "/api/remove-from-cart",
            data: JSON.stringify({
                product_id: productId
            }),
            contentType: "application/json",
            success: function (response) {
                showAlert(response.message, 'success');
                item.remove();
                updateCartTotal();
                fetchCarts(); // Refresh cart items after removing from cart
            },
            error: function () {
                showAlert('Error removing item from cart.', 'danger');
            }
        });
    });

    function updateCartTotal() {
        let total = 0;
        $('#customerCarts .cart-item').each(function () {
            const partialTotal = parseFloat($(this).find('.partialTotal').text().replace('₱', ''));
            total += partialTotal;
        });
        $('.totalCartAmount').text(formatCurrency(total));
    }

    function formatCurrency(value) {
        return '₱' + (Math.round(value * 100) / 100).toString().replace(/(\.\d*?[1-9])0+$/, '$1').replace(/\.(?!.*\d)/, '');
    }

    function fetchCarts() {
        $.ajax({
            url: '/api/carts',
            method: 'GET',
            success: function(data) {
                console.log('API Response:', data);
                if (data.carts && data.carts.length > 0) {
                    renderCarts(data.carts);
                    $('#checkoutButton').show(); // Show the checkout button if there are items in the cart
                } else {
                    $('#customerCarts').html(`
                        <tr>
                            <td colspan="5" class="text-center">Your Cart is Empty</td>
                        </tr>
                    `);
                    $('#checkoutButton').hide(); // Hide the checkout button if the cart is empty
                }
                $('.totalCartAmount').text(`₱${data.cartTotal}`);
            },
            error: function(xhr, status, error) {
                console.error('Error fetching cart data:', error);
            }
        });
    }

    function renderCarts(carts) {
        const $cartsBody = $('#customerCarts');
        $cartsBody.empty();

        carts.forEach(function(cart) {
            const partialTotal = cart.cost * cart.pivot_cart_qty;

            $cartsBody.append(`
                <tr class="cart-item" data-id="${cart.id}" data-price="${cart.cost}">
                    <td class="itemName" data-id="${cart.id}">
                        <img src="${cart.img_path}" alt="${cart.name}" style="width: 65px; height: 65px; object-fit: contain;"/>
                        ${cart.name}
                    </td>
                    <td class="quantity">
                        <button class="btn btn-sm btn-secondary fa fa-minus" style="background-color: white; color: black; border: 1px solid black; font-size: 12px; padding: 10px; border-radius: 5px;"></button>
                        <span class="quantity-value">${cart.pivot_cart_qty}</span>
                        <button class="btn btn-sm btn-secondary fa fa-plus" style="background-color: white; color: black; border: 1px solid black; font-size: 12px; padding: 10px; border-radius: 5px;"></button>
                    </td>
                    <td class="price">₱${cart.cost}</td>
                    <td class="partialTotal">₱${partialTotal}</td>
                    <td class="actions">
                        <button class="btn btn-sm btn-danger fa fa-trash" style="background-color: white; color: red ; border: 1px solid black; font-size: 15px; padding: 10px; border-radius: 5px;"></button>
                    </td>
                </tr>
            `);
        });
    }

    fetchCarts();

    function checkout() {
        $.ajax({
            url: '/api/checkout',
            method: 'GET',
            success: function(data) {
                console.log('API Response:', data);
                if (data.carts && data.carts.length > 0) {
                    checkoutDetails(data.carts);
                } else {
                    $('#customerCheckout').html(`
                        <tr>
                            <td colspan="4" class="text-center">Your Cart is Empty</td>
                        </tr>
                    `);
                }
                $('.orderTotal').text(`₱${data.cartTotal}`);
                $('.shippingFee').text(`₱${data.shippingFee}`);
                $('.totalAmount').text(`₱${data.totalAmount}`);
                displayCustomerDetails(data.customer);
                populateCouriers(data.couriers);
                populateMethods(data.payment_methods);
            },
            error: function(xhr, status, error) {
                console.error('Error fetching cart data:', error);
            }
        });
    }


    function checkoutDetails(carts) {
        const $checkoutBody = $('#customerCheckout');
        $checkoutBody.empty();

        carts.forEach(function(cart) {
            const partialTotal = cart.cost * cart.pivot_cart_qty;

            $checkoutBody.append(`
                <tr class="cart-item" data-id="${cart.id}" data-price="${cart.cost}">
                    <td class="itemName" data-id="${cart.id}">
                        <img src="${cart.img_path}" alt="${cart.name}" style="width: 50px; height: 50px; object-fit: cover;"/>
                        ${cart.name}
                    </td>
                    <td class="quantity">
                        <span class="quantity-value">${cart.pivot_cart_qty}</span>
                    </td>
                    <td class="price">₱${cart.cost}</td>
                    <td class="partialTotal">₱${partialTotal}</td>
                </tr>
            `);
        });
    }

    function displayCustomerDetails(customer) {
        if (customer) {
            const customerDetails = `
                <div class="customer-details" style="
                    border: 1px solid #ddd;
                    border-radius: 8px;
                    padding: 15px;
                    background-color: #f9f9f9;
                    max-width: 400px;
                    font-family: 'Quicksand', sans-serif;
                ">
                    <h3 style="
                        margin-bottom: 15px;
                        color: #333;
                        font-size: 1.5em;
                        font-weight: bold;
                    ">Delivery Details:</h3>
                    <p style="
                        margin: 5px 0;
                        font-size: 1em;
                        color: #555;
                    "><strong>Name:</strong> ${customer.name}</p>
                    <p style="
                        margin: 5px 0;
                        font-size: 1em;
                        color: #555;
                    "><strong>Contact Number:</strong> ${customer.contact_number}</p>
                    <p style="
                        margin: 5px 0;
                        font-size: 1em;
                        color: #555;
                    "><strong>Email:</strong> ${customer.email}</p>
                    <p style="
                        margin: 5px 0;
                        font-size: 1em;
                        color: #555;
                    "><strong>Address:</strong> ${customer.address}</p>
                </div>
            `;
            $('#customerDetails').html(customerDetails);
        }
    }


    function populateCouriers(couriers) {
        const $courierSelect = $('#courierSelect');
        $courierSelect.empty();
        couriers.forEach(function(courier) {
            $courierSelect.append(`
                <option value="${courier.id}">${courier.courier_name}</option>
            `);
        });
    }

    function populateMethods(payment_methods) {
        const $methodSelect = $('#methodSelect');
        $methodSelect.empty();
        payment_methods.forEach(function(payment_method) {
            $methodSelect.append(`
                <option value="${payment_method.id}">${payment_method.payment_method}</option>
            `);
        });
    }
    checkout();

    $('#placeOrder').click(function () {
        var items = [];
        $('#customerCheckout .cart-item').each(function () {
            var item = $(this);
            items.push({
                id: item.data('id'),
                quantity: parseInt(item.find('.quantity-value').text(), 10),
                price: parseFloat(item.find('.price').text().replace('₱', '').trim())
            });
        });
    
        const courierId = $('#courierSelect').val();
        const paymentMethodId = $('#methodSelect').val();
    
        Swal.fire({
            title: 'Confirm Order',
            text: "Are you sure you want to place this order?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, place order!',
            cancelButtonText: 'No, cancel!',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    url: "/api/place-order",
                    data: JSON.stringify({
                        items: items,
                        courier_id: courierId,
                        payment_method_id: paymentMethodId
                    }),
                    contentType: "application/json",
                    success: function (response) {
                        if (response.code === 200) {
                            Swal.fire(
                                'Success!',
                                'Order successfully processed.',
                                'success'
                            ).then(() => {
                                window.location.href = '/carts';
                            });
                        } else {
                            Swal.fire(
                                'Error',
                                'Error: ' + response.error,
                                'error'
                            );
                        }
                    },
                    error: function (xhr, status, error) {
                        Swal.fire(
                            'Error',
                            'Error checking out: ' + error,
                            'error'
                        );
                    }
                });
            }
        });
    });
    
    // $('#placeOrder').click(function () {
    //     var items = [];
    //     $('#customerCheckout .cart-item').each(function () {
    //         var item = $(this);
    //         items.push({
    //             id: item.data('id'),
    //             quantity: parseInt(item.find('.quantity-value').text(), 10),
    //             price: parseFloat(item.find('.price').text().replace('₱', '').trim())
    //         });
    //     });

    //     const courierId = $('#courierSelect').val();
    //     const paymentMethodId = $('#methodSelect').val();

    //     $.ajax({
    //         type: "POST",
    //         url: "/api/place-order",
    //         data: JSON.stringify({
    //             items: items,
    //             courier_id: courierId,
    //             payment_method_id: paymentMethodId
    //         }),
    //         contentType: "application/json",
    //         success: function (response) {
    //             if (response.code === 200) {
    //                 showAlert('Order successfully processed.', 'success');
    //                 window.location.href = '/carts';
    //             } else {
    //                 showAlert('Error: ' + response.error, 'danger');
    //             }
    //         },
    //         error: function (xhr, status, error) {
    //             showAlert('Error checking out: ' + error, 'danger');
    //         }
    //     });
    // });
});
