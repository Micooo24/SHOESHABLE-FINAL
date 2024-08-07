@extends('layouts.app')

@section('title', 'Brand Dashboard')

@section('content')
<div id="flash-message" class="alert" style="display: none;"></div>
<div class="container mt-4" style="max-width: 1200px; margin-left: 257px;">
    <div class="card mb-4 mt-3"style="border: none;">
        <div class="card-header text-center mt-2" style="background-color: lightskyblue; border:2px solid black;">
            <h5 class="card-title">IMPORT DATA</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12 mb-3 mt-2">
                    <nav class="navbar navbar-expand-lg navbar-light bg-light">
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarNav">
                            <ul class="navbar-nav">
                                <li class="nav-item">
                                    <a class="btn btn-outline-dark nav-link" href="#" onclick="showSection('brands')" style="margin: 5px; border-width: 2px; transition: none; background-color: transparent; color: inherit;">Brands</a>
                                </li>
                                <li class="nav-item">
                                    <a class="btn btn-outline-dark nav-link" href="#" onclick="showSection('couriers')" style="margin: 5px; border-width: 2px; transition: none; background-color: transparent; color: inherit;">Couriers</a>
                                </li>
                                <li class="nav-item">
                                    <a class="btn btn-outline-dark nav-link" href="#" onclick="showSection('products')" style="margin: 5px; border-width: 2px; transition: none; background-color: transparent; color: inherit;">Products</a>
                                </li>
                                <li class="nav-item">
                                    <a class="btn btn-outline-dark nav-link" href="#" onclick="showSection('suppliers')" style="margin: 5px; border-width: 2px; transition: none; background-color: transparent; color: inherit;">Suppliers</a>
                                </li>
                            </ul>
                        </div>
                    </nav>
                </div>

                <div class="col-12">
                    <div class="input-group-custom">
                        <form id="multiple-importForm" enctype="multipart/form-data">
                            <input type="file" name="importMultipleSheet" id="importMultipleSheet" />
                            <button type="submit" id="import-button" class="btn btn-primary"style="float: right;">IMPORT ALL</button>
                        </form>
                    </div>
                </div>

                <div id="brands" class="col-12 mt-4 table-section" style="display:none;">
                    <div class="card mb-4">
                        <div class="card-header text-center mt-2 ml-2 mr-2" style="background-color:transparent; border:2px solid black;">
                            <h3>BRANDS</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="adminBrand" class="table table-hover w-100">
                                    <tbody id="tbody"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="couriers" class="col-12 mt-4 table-section" style="display:none;">
                    <div class="card mb-4">
                        <div class="card-header text-center mt-2 ml-2 mr-2" style="background-color:transparent; border:2px solid black;">
                            <h3>COURIERS</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="adminCourier" class="table table-hover w-100">
                                    <tbody id="tbody"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="products" class="col-12 mt-4 table-section">
                    <div class="card mb-4">
                        <div class="card-header text-center mt-2 ml-2 mr-2" style="background-color:transparent; border:2px solid black;">
                            <h3>PRODUCTS</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="adminProduct" class="table table-hover w-100">
                                    <tbody id="tbody"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="suppliers" class="col-12 mt-4 table-section" style="display:none;">
                    <div class="card mb-4">
                        <div class="card-header text-center mt-2 ml-2 mr-2" style="background-color:transparent; border:2px solid black;">
                            <h3>SUPPLIERS</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="adminSupplier" class="table table-hover w-100">
                                    <tbody id="tbody"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
function showSection(sectionId) {
    // Hide all sections
    document.querySelectorAll('.table-section').forEach(section => {
        section.style.display = 'none';
    });

    // Show the selected section
    const section = document.getElementById(sectionId);
    section.style.display = 'block';

    // Trigger a resize event to ensure table layout is correct
    window.dispatchEvent(new Event('resize'));
}

// Show the products section by default
document.addEventListener('DOMContentLoaded', (event) => {
    showSection('products');
});
</script>

@endsection
