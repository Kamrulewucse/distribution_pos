@extends('layouts.app')
@section('title')
    Product Assign Order List
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

        });
    </script>
@endsection
