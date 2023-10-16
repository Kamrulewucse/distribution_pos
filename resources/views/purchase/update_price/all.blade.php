@extends('layouts.app')
@section('title')
    Product Price Update
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
                            <th>Model No</th>
                            <th>Old Purchase Price</th>
                            <th>Old Selling Price</th>
                            <th>New Purchase Price</th>
                            <th>New Selling Price</th>
                            <th>Total Product</th>
                            <th>Total Price</th>

                        </tr>
                        </thead>

                        <tbody>
                        @foreach($updatePrices as $updatePrice)
                            <tr>
                                <td>{{ $updatePrice->date}}</td>
                                <td>{{ $updatePrice->productModel->name }}</td>
                                <td>{{ $updatePrice->old_purchase_price }}</td>
                                <td>{{ $updatePrice->old_selling_price }}</td>
                                <td>{{ $updatePrice->updated_purchase_price }}</td>
                                <td>{{ $updatePrice->updated_selling_price }}</td>
                                <td>{{ $updatePrice->total_quantity }}</td>
                                <td>{{ $updatePrice->total_price }}</td>
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

    <script>
        $(function () {
            $('#table').DataTable();
        })

    </script>
@endsection
