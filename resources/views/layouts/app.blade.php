
<!DOCTYPE html>
<html>
<head>
    <title>SHOESSSHABLE</title>
    <meta charset="utf-8">
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.ico">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="api-token" content="{{ session('api-token') }}">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables.net-buttons-bs5/2.3.6/buttons.bootstrap5.min.css">
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Boxicons CSS -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>
    <!-- DataTables Buttons -->
    <script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.bootstrap4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.5/js/buttons.html5.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>
    <!-- Bootbox.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.5.2/bootbox.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Your custom JavaScript files -->

    <script src="{{ asset('js/crud/product.js') }}"></script>
    <script src="{{ asset('js/crud/courier.js') }}"></script>
    <script src="{{ asset('js/crud/supplier.js') }}"></script>
    <script src="{{ asset('js/crud/brand.js') }}"></script>

    <script src="{{ asset('js/admin/stock.js') }}"></script>
    <script src="{{ asset('js/admin/user.js') }}"></script>
    <script src="{{ asset('js/admin/order.js') }}"></script>
    <script src="{{ asset('js/admin/chart.js') }}"></script>
    <script src="{{ asset('js/admin/payment.js') }}"></script>
    <script src="{{ asset('js/admin/dashboard.js') }}"></script>
    <script src="{{ asset('js/admin/crud.js') }}"></script>

    <script src="{{ asset('js/design/sidebar.js') }}"></script>
    <script src="{{ asset('js/design/header.js') }}"></script>
    <script src="{{ asset('js/script.js') }}"></script>
    <script src="{{ asset('js/profile.js') }}"></script>

    <!-- Include dropzone CSS -->
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css">
    <!-- Include DataTables Buttons CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.5/css/buttons.dataTables.min.css">
    <!-- Validation Jquery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
</head>
<body>
<div id="app">
    @include('layouts.sidebar')
    <div class="content">
        @yield('content')
    </div>
</div>
</body>
</html>
