@extends('layouts.app')

@section('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endsection

@section('title')
    Retailer
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
                    <a class="btn btn-primary" href="{{ route('customer.add') }}">Add Retailer</a>
                    <hr>
                    <table id="table" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Mobile</th>
                            <th>Email</th>
                            <th>Address</th>
                            <th>Shop Name</th>
                            <th>Total</th>
                            <th>Paid</th>
                            <th>Due</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($customers as $customer)
                            <tr>
                                <td>{{ $customer->name }}</td>
                                <td>{{ $customer->mobile_no }}</td>
                                <td>{{ $customer->email }}</td>
                                <td>{{ $customer->address }}</td>
                                <td>{{ $customer->shop_name }}</td>
                                <td>{{ $customer->total }}</td>
                                <td>{{ $customer->paid }}</td>
                                <td>{{ $customer->due }}</td>
                                <td>
                                    <a class="btn btn-info btn-sm" href="{{ route('customer.edit', ['customer' => $customer->id]) }}">Edit</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
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

{{--    <script>--}}
{{--        $(function () {--}}
{{--            $('#table').DataTable({--}}
{{--                processing: true,--}}
{{--                serverSide: true,--}}
{{--                ajax: '{{ route('customer.datatable') }}',--}}
{{--                columns: [--}}
{{--                    {data: 'name', name: 'name'},--}}
{{--                    {data: 'mobile_no', name: 'mobile_no'},--}}
{{--                    {data: 'address', name: 'address'},--}}
{{--                    {data: 'shop_name', name: 'shop_name'},--}}
{{--                    {data: 'action', name: 'action', orderable: false},--}}
{{--                ],--}}
{{--            });--}}
{{--        })--}}
{{--    </script>--}}
@endsection
