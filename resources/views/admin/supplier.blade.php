@extends('layouts.app')
@section('title', 'Supplier Dashboard')
@section('content')
<div id="flash-message" class="alert" style="display: none;"></div>
<div class="container mt-1 mr-2" style="width: 84%; max-width: 1200px;">
        <div class="card mt-3 mb-3">
            <div class="card-header mt-2 ml-5 mb-2 mr-5 text-center" style="background-color: lightskyblue; border:2px solid black;" >
                <h5 class="card-title">Supplier Management</h5>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 position-relative">
                <div class="card mt-3 mb-3">
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
                        <form id="import-form-supplier" enctype="multipart/form-data">
                            <input type="file" name="importFile" id="importFile" />
                            <button type="submit" id="import-button" class="btn btn-primary">Import Suppliers</button>
                        </form>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive mt-3">
                            <table id="suppliertable" class="table table-hover">
                                <tbody id="supplierbody">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<div class="modal fade" id="supplierModal" role="dialog" style="display:none">
    <div class="modal-dialog modal-lg">
        <div class="modal-content custom-modal-content">
            <div class="card mt-3 mb-3 ml-3 mr-3">
                <div class="card-body mt-1 mb-1 ml-1 mr-1">
                    <div class="card-header mt-2 ml-5 mb-2 mr-5 text-center" style="background-color: lightskyblue; border:2px solid black;" >
                    <h4 class="modal-title">SUPPLIER</h4>
                </div>
                </div>
                <div class="card-body">
                    <form id="supplierform" method="#" action="#" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="name_id" class="control-label">Name</label>
                            <input type="text" class="form-control" id="name_id" name="name">
                        </div>
                        <div class="form-group">
                            <label for="email_id" class="control-label">Email</label>
                            <input type="text" class="form-control" id="email_id" name="email">
                        </div>
                        <div class="form-group">
                            <label for="contact_id" class="control-label">Contact Number</label>
                            <input type="text" class="form-control" id="contact_id" name="contact_number">
                        </div>
                        <div class="form-group">
                            <label for="image" class="control-label">Image</label>
                            <input type="file" class="form-control" id="image" name="uploads[]" multiple>
                        </div>
                    </form>
                </div>
                <div class="card-footer mt-2 ml-5 mb-2 mr-5 text-center" style="background-color: lightskyblue; border:2px solid black;" >
                    <button id="supplierSubmit" type="submit" class="btn btn-primary">Save</button>
                    <button id="supplierUpdate" type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
