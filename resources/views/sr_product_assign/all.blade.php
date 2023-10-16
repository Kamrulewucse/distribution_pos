@extends('layouts.app')
@section('title')
    SR Product Assign Receipt
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
                    <table id="table" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Date</th>
                            <th>Order No</th>
                            <th>SR</th>
                            <th>Sale Amount</th>
                            <th>Sale Paid Amount</th>
                            <th>Sale Due Amount</th>
                            <th>Assign Quantity</th>
                            <th>Sale Quantity</th>
                            <th>Back Quantity</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-assign-order-close">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Assign Order Close Information</h4>
                </div>
                <div class="modal-body">
                    <form id="modal-assign-order-form" enctype="multipart/form-data" name="modal-assign-order-form">
                        <input type="hidden" id="order" name="order">
                        <div class="form-group">
                            <label>Date *</label>
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control pull-right date-picker" id="date" name="date" value="{{ date('Y-m-d') }}" autocomplete="off">
                            </div>
                            <!-- /.input group -->
                        </div>

                        <div class="form-group">
                            <label>Note</label>
                            <input class="form-control" name="note" placeholder="Enter Note">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="modal-btn-assign-close-save">Save</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
@endsection

@section('script')

    <script>
        $(function () {

            $('#table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('sr_product_assign.datatable') }}',
                columns: [
                    {data: 'date', name: 'date'},
                    {data: 'order_no', name: 'order_no'},
                    {data: 'sr_name', name: 'sr.name'},
                    {data: 'total_sale_amount', name: 'total_sale_amount'},
                    {data: 'total_paid_amount', name: 'total_paid_amount'},
                    {data: 'total_due_amount', name: 'total_due_amount'},
                    {data: 'assign_quantity', name: 'assign_quantity'},
                    {data: 'sale_quantity', name: 'sale_quantity'},
                    {data: 'back_quantity', name: 'back_quantity'},
                    {data: 'status', name: 'action', status: false},
                    {data: 'action', name: 'action', orderable: false},
                ],
                order: [[ 0, "desc" ]],
            });

            $('body').on('click', '.btn-assign-close', function () {
                $("#order").val($(this).data('id'));
                $('#modal-assign-order-close').modal('show');
            });
            $('#modal-btn-assign-close-save').click(function () {
                var formData = new FormData($('#modal-assign-order-form')[0]);

                $.ajax({
                    type: "POST",
                    url: "{{ route('sr_assign_product_order.close') }}",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            $('#modal-assign-order-close').modal('hide');
                            Swal.fire(
                                'Assign Order Closed!',
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
        });
    </script>
@endsection
