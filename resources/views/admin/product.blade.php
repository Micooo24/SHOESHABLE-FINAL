@extends('layouts.app')
@section('title', 'Product Dashboard')
@section('content')
<div id="flash-message" class="alert" style="display: none;"></div>
<div class="container mt-1 mr-2" style="width: 84%; max-width: 1200px;">
    <div class="card mt-3 mb-3">
        <div class="card-header mt-2 ml-5 mb-2 mr-5 text-center" style="background-color: lightskyblue; border:2px solid black;" >
            <h5 class="card-title">Product Management</h5>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card mt-3 mb-3 position-relative">
            <button id="refreshButton" class="btn" style="
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 50px;
            padding: 10px 20px;
            font-size: 14px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            position: absolute;
            top: 10px;
            right: 10px;
            transition: background-color 0.3s, box-shadow 0.3s;
        ">
            <i class="fa fa-refresh" style="margin-right: 5px;"></i> Refresh
             </button>
                <div class="card-body">
                    @include('layouts.flash-messages')
                </div>

                <div class="input-group-custom">
                    <form id="import-form-product" enctype="multipart/form-data">
                        <input type="file" name="importFile" id="importFile" />
                        <button type="submit" id="import-button" class="btn btn-primary">Import Products</button>
                    </form>
                </div>

                <div class="card-body">
                    <div class="table-responsive mt-3">
                        <table id="productTable" class="table table-hover">
                            <tbody id="productTbody">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="productModal" role="dialog" style="display:none">
    <div class="modal-dialog modal-lg">
        <div class="modal-content custom-modal-content">
            <div class="card mt-3 mb-3 ml-3 mr-3">
                <div class="card-body mt-1 mb-1 ml-1 mr-1">
                    <div class="card-header mt-2 ml-5 mb-2 mr-5 text-center" style="background-color: lightskyblue; border:2px solid black;" >
                    <h4 class="modal-title">PRODUCT</h4>
                </div>
                </div>
                <div class="card-body">
                    <form id="productform" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="name_id" class="control-label">Name</label>
                            <input type="text" class="form-control" id="name_id" name="name">
                        </div>
                        <div class="form-group">
                            <label for="brand_id" class="col-sm-2 control-label">Brand</label>
                            <div class="col-sm-12">
                                <select name="brand_id" id="brand_id" class="form-control">
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="supplier_id" class="col-sm-2 control-label">Supplier</label>
                            <div class="col-sm-12">
                                <select name="supplier_id" id="supplier_id" class="form-control">
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="description_id" class="control-label">Description</label>
                            <input type="text" class="form-control" id="description_id" name="description">
                        </div>
                        <div class="form-group">
                            <label for="cost_id" class="control-label">Cost</label>
                            <input type="text" class="form-control" id="cost_id" name="cost">
                        </div>
                        <div class="form-group">
                            <label for="quantity_id" class="control-label">Quantity</label>
                            <input type="number" class="form-control" id="quantity_id" name="quantity" >
                        </div>
                        <div class="form-group">
                            <label for="image" class="control-label">Image</label>
                            <input type="file" class="form-control" id="image" name="uploads[]" multiple />
                        </div>
                    </form>
                </div>
                <div class="card-footer mt-2 ml-5 mb-2 mr-5 text-center" style="background-color: lightskyblue; border:2px solid black;" >
                    <button id="productSubmit" type="submit" class="btn btn-primary">Save</button>
                    <button id="productUpdate" type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
