@extends('layouts.app')

@section('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
@endsection

@section('title')
    Purchase Inventory
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
                            <th>Category</th>
                            <th>Sub Category</th>
                            <th>Product</th>
                            <th>Warehouse</th>
                            <th>Purchase Qty</th>
                            <th>Sale Qty</th>
                            <th>Stock Qty</th>
                            <th>Last Price</th>
                            <th>Avg Price</th>
                            <th>Total Selling</th>
                            <th>Total Purchase</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <!-- DataTables -->
    <script src="{{ asset('themes/backend/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('themes/backend/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
    <!-- bootstrap datepicker -->
    <script src="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <!-- sweet alert 2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

    <script>
        $(function () {
            $('#table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('purchase_inventory.datatable') }}',
                columns: [
                    {data: 'category', name: 'product.category.name'},
                    {data: 'subcategory', name: 'product.subcategory.name'},
                    {data: 'product', name: 'product.name'},
                    {data: 'warehouse', name: 'warehouse.name'},
                    {data: 'total_purchase_qty', name: 'total_purchase_qty'},
                    {data: 'total_sell_qty', name: 'total_sell_qty'},
                    {data: 'quantity', name: 'quantity'},
                    {data: 'last_unit_price', name: 'last_unit_price'},
                    {data: 'avg_unit_price', name: 'avg_unit_price'},
                    {data: 'selling_price', name: 'selling_price'},
                    {data: 'total', name: 'total'},
                ],
            });
        });
    </script>
@endsection
