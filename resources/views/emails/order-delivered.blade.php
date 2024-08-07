@component('mail::message')
<style>
    .email-container {
        font-family: Arial, sans-serif;
        text-align: center;
        background-color: #f8f9fa;
        padding: 20px;
        border-radius: 10px;
    }
    .email-header img {
        width: 80px;
        height: 80px;
    }
    .email-title {
        font-size: 24px;
        font-weight: bold;
        color: #343a40;
    }
    .email-subtitle {
        font-size: 18px;
        color: #6c757d;
        margin: 10px 0;
    }
    .email-content {
        background-color: #ffffff;
        padding: 20px;
        border-radius: 10px;
        text-align: left;
        margin: 20px 0;
    }
    .product-item {
        display: flex;
        justify-content: space-between;
        padding: 10px;
        border-bottom: 1px solid #e9ecef;
    }
    .product-item:last-child {
        border-bottom: none;
    }
    .product-name {
        font-weight: bold;
        color: #343a40;
    }
    .product-quantity {
        color: #6c757d;
    }
    .email-footer {
        margin-top: 20px;
        font-size: 14px;
        color: #6c757d;
    }
    .email-footer img {
        width: 50px;
        height: 50px;
        margin-top: 10px;
    }
</style>

<div class="email-container">
    <div class="email-header">
        <img src="{{ url('photo/logo.png') }}" alt="Logo">
    </div>
    <div class="email-title">
        Order Receipt
    </div>
    <div class="email-subtitle">
        Dear {{ $order->customer->user->name }},
    </div>
    <div class="email-content">
        <p>Thank you for your order. Below are the details of your delivered order:</p>
        <p>
            <strong>Username:</strong> {{ $order->customer->username }} <br>
            <strong>Date Delivered:</strong> {{ $order->date_delivered->format('M d, Y') }}
        </p>
        <hr>
        <p><strong>Products:</strong></p>
        <div>
            @foreach($order->products as $product)
                <div class="product-item">
                    <span class="product-name">{{ $product->name }}</span>
                    <span class="product-quantity"> Quantity: <strong>{{ $product->pivot->quantity }}</strong></span>
                </div>
            @endforeach
        </div>
        @php
            $totalAmount = 0;
            foreach ($order->products as $product) {
                $totalAmount += $product->cost * $product->pivot->quantity;
            }
        @endphp
        <p><strong>Total Amount:</strong> Php {{ number_format($totalAmount, 2) }}</p>
    </div>
    <div class="email-footer">
        <p>If you have any questions or concerns, please feel free to contact us.</p>
        <p>Thank you,</p>
        <p>
            <img src="{{ url('photo/logo.png') }}" alt="Logo">
        </p>
        <p>Shoessshables</p>
    </div>
</div>
@endcomponent
