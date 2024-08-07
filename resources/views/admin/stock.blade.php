@extends('layouts.app')
@section('title', 'Stock Dashboard')
@section('content')
<div id="flash-message" class="alert" style="display: none;"></div>
<div class="container mt-1 mr-2" style="width: 84%; max-width: 1200px;">
        <div class="card mt-3 mb-3">
            <div class="card-header mt-2 ml-5 mb-2 mr-5 text-center" style="background-color: lightskyblue; border:2px solid black;" >
                <h5 class="card-title">Stock Management</h5>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card mt-3 mb-3">
                    <div class="card-body">
                        @include('layouts.flash-messages')
                    </div>
                    <div class="card-body">
                        <div class="table-responsive mt-3">
                            <table id="stockTable" class="table table-hover">
                                <tbody id="stockTbody">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="stockModal" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Inventory</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="stockform" method="#" action="#" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="prod_name_id" class="control-label">Product Name</label>
                                <input type="text" class="form-control" id="prod_name_id" name="product_name">
                            </div>
                            <div class="form-group">
                                <label for="brand_name_id" class="control-label">Brand Name</label>
                                <input type="text" class="form-control" id="brand_name_id" name="brand_name">
                            </div>
                            <div class="form-group">
                                <label for="supplier_name_id" class="control-label">Supplier Name</label>
                                <input type="text" class="form-control" id="supplier_name_id" name="supplier_name">
                            </div>
                            <div class="form-group">
                                <label for="quantity_id" class="control-label">Quantity</label>
                                <input type="number" class="form-control" id="quantity_id" name="quantity" >
                            </div>

                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button id="stockSubmit" type="submit" class="btn btn-primary">Save</button>
                        <button id="stockUpdate" type="submit" class="btn btn-primary">Update</button>
                    </div>
                </div>
            </div>
        </div>
</div>
@endsection
