@extends('layouts.app')
@section('title', 'Payment Method Dashboard')
@section('content')
<div id="flash-message" class="alert" style="display: none;"></div>
<div class="container mt-1 mr-2" style="width: 84%; max-width: 1200px;">
        <div class="card mt-3 mb-3">
            <div class="card-header mt-2 ml-5 mb-2 mr-5 text-center" style="background-color: lightskyblue; border:2px solid black;" >
                <h5 class="card-title">Payment Method Management</h5>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card mt-3 mb-3">
                    <div class="card-body">
                    </div>
                    <div class="card-body">
                        <div class="table-responsive mt-3">
                            <table id="paymentTable" class="table table-hover">
                                <tbody id="paymentTbody">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="paymentModal" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Payment Method</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="paymentform" method="#" action="#" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="payment_name_id" class="control-label">Payment Method Name</label>
                                <input type="text" class="form-control" id="payment_name_id" name="payment_method">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button id="paymentSubmit" type="submit" class="btn btn-primary">Save</button>
                        <button id="paymentUpdate" type="submit" class="btn btn-primary">Update</button>
                    </div>
                </div>
            </div>
        </div>
</div>
@endsection
