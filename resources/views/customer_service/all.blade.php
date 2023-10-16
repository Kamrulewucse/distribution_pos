@extends('layouts.app')

@section('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endsection

@section('title')
    Customer Service Information
@endsection

@section('content')
    @if(Session::has('message'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            {{ Session::get('message') }}
        </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    <a class="btn btn-primary" href="{{ route('customer_service.add') }}">Add Customer</a>
                    <hr>
                    <table id="table" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Note</th>
                            <th>Mobile Details</th>
                            <th>Mobile</th>
                            <th>Address</th>
                            <th>Receive Date</th>
                            <th>Delivery Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($customerServices as $customer)
                            <tr>
                                <td>{{ $customer->name }}</td>
                                <td>{{ $customer->note }}</td>
                                <td>{{ $customer->mobile_name }}</td>
                                <td>{{ $customer->mobile_no }}</td>
                                <td>{{ $customer->address }}</td>
                                <td>{{ $customer->date }}</td>
                                <td>{{ $customer->delivery_date }}</td>
                                <td>
                                    @if ($customer->status == 0)
                                        <span class="label label-warning">Receive from Customer</span>
                                    @elseif ($customer->status == 1)
                                        <span class="label label-success">Pending</span>
                                    @elseif ($customer->status == 2)
                                        <span class="label label-success">Receive From Company</span>
                                    @elseif ($customer->status == 3)
                                        <span class="label label-success">Delivery Completed</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($customer->status == 0)
                                        <a class=" btn btn-info btn-sm" href="{{ route('customer_service.edit', ['customerService' => $customer->id]) }}">Edit</a>
                                        <a class="btn btn-primary btn-sm btn-pending" data-id = {{$customer->id}} id="btn-pending">Pending</a>
                                    @elseif($customer->status == 1)
                                        <a class="btn btn-primary btn-sm receive-from-company" data-id = {{$customer->id}} id="receive-from-company">Receive From Compnay</a>
                                    @elseif($customer->status == 2)
                                        <a role="button" class="btn btn-warning btn-sm" onclick="delivery({{$customer->id}},{{$customer->delivery_date}})">Delivery</a>
                                    @elseif($customer->status == 3)
                                        <a class="btn btn-danger btn-sm btn-delete" data-id = {{$customer->id}} id="btn-delete">Delete</a>
                                    @endif

                                </td>

                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-edit">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"></span></button>
                    <h4 class="modal-title">Delivery Details</h4>
                </div>
                <div class="modal-body">
                    <form id="modal-edit-form" enctype="multipart/form-data" name="modal-edit-form">

                        <div class="form-group">
                            <input type="hidden" id="customer_id" name="customer_id">
                            <label>Delivery Note</label>
                            <input class="form-control" id="delivery_note" name="delivery_note" placeholder="Delivery Details">
                        </div>
                        <div class="form-group {{ $errors->has('delivery_date') ? 'has-error' :'' }}">
                            <label>Delivery Date</label>
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control pull-right date-picker"  name="delivery_date" value="{{ empty(old('delivery_date')) ? ($errors->has('delivery_date') ? '' : date('Y-m-d')) : old('delivery_date') }}" autocomplete="off">
                            </div>
                            <!-- /.input group -->

                            @error('delivery_date')
                            <span class="help-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="modal-btn-update">Save</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
@endsection

@section('script')
    <!-- DataTables -->
    <script src="{{ asset('themes/backend/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('themes/backend/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>

    <script>
        $(function () {
            $('body').on('click', '#btn-pending', function () {
                let customerId = $(this).data('id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Pending It!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "POST",
                            url: "{{route('product_pending')}}",
                            data: { id: customerId },
                        }).done(function( response ) {

                            if (response.success) {

                                Swal.fire(
                                    'Pending!',
                                    response.message,
                                    'success'
                                ).then((result) => {
                                    location.reload();
                                });
                            } else {

                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: response.message,
                                });
                            }
                        });

                    }
                })

            });
            $('body').on('click', '#receive-from-company', function () {
                let customerId = $(this).data('id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Receive It!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "POST",
                            url: "{{route('receive_from_company')}}",
                            data: { id: customerId },
                        }).done(function( response ) {

                            if (response.success) {

                                Swal.fire(
                                    'Receive!',
                                    response.message,
                                    'success'
                                ).then((result) => {
                                    location.reload();
                                });
                            } else {

                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: response.message,
                                });
                            }
                        });

                    }
                })

            });

            $('body').on('click', '#btn-delete', function () {
                let customerId = $(this).data('id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Delete It!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "POST",
                            url: "{{route('delete_customer')}}",
                            data: { id: customerId },
                        }).done(function( response ) {

                            if (response.success) {
                                Swal.fire(
                                    'Delete!',
                                    response.message,
                                    'success'
                                ).then((result) => {
                                    location.reload();
                                });
                            } else {

                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: response.message,
                                });
                            }
                        });

                    }
                })

            });

            $('#modal-btn-update').click(function () {
                var formData = new FormData($('#modal-edit-form')[0]);
                $.ajax({
                    type: "POST",
                    url: "{{ route('delivery_complete')}}",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            $("#modal-edit").modal("hide");
                            Swal.fire(
                                'Updated!',
                                response.message,
                                'success'
                            ).then((result) => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: response.message,
                            });
                        }
                    }
                });
            });

        })

        function delivery(customer_id,delivery_date){
            $("#customer_id").val(customer_id);
            $("#delivery_date").val(delivery_date);
            $("#modal-edit").modal("show");
        }
    </script>
@endsection
