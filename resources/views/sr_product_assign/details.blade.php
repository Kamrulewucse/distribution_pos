@extends('layouts.app')

@section('title')
    SR Product Assign Receipt Details
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12 text-right">
                            <a role="button" class="btn btn-primary">Print</a>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Order No.</th>
                                    <td>{{ $order->order_no }}</td>
                                </tr>
                                <tr>
                                    <th>Order Date</th>
                                    <td>{{ $order->date->format('j F, Y') }}</td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th colspan="2" class="text-center">SR Info</th>
                                </tr>
                                <tr>
                                    <th>Name</th>
                                    <td>{{ $order->sr->name ?? '' }}</td>
                                </tr>
                                <tr>
                                    <th>Mobile</th>
                                    <td>{{ $order->sr->mobile_no ?? '' }}</td>
                                </tr>
                                <tr>
                                    <th>Address</th>
                                    <td>{{ $order->sr->address ?? '' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>

                                        <th>Product Brand</th>
                                        <th>Product Model</th>
                                        <th>Assign Quantity</th>
                                        <th>Sale Quantity</th>
                                        <th>Back Quantity</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($order->srAssignProductItem as $item)
                                        <tr>
                                            <td>{{$item->productBrand->name ?? ''}}</td>
                                            <td>{{$item->productModel->name ?? ''}}</td>
                                            <td>{{ $item->assign_quantity}}</td>
                                            <td>{{ $item->sale_quantity}}</td>
                                            <td>{{ $item->back_quantity}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')

    <script>
        $(function () {

        });
    </script>
@endsection
