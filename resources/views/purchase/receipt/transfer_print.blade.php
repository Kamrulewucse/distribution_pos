<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!--Favicon-->
    <link rel="icon" href="{{ asset('img/favicon.png') }}" type="image/x-icon" />

    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-xs-6">
            <table class="table table-bordered">
                <tr>
                    <th>Transfer Order No.</th>
                    <td>{{ $order->order_no }}</td>
                </tr>
                <tr>
                    <th>Order Date</th>
                    <td>{{ $order->date->format('j F, Y') }}</td>
                </tr>
            </table>
        </div>

{{--        <div class="col-xs-6">--}}
{{--            <table class="table table-bordered">--}}
{{--                <tr>--}}
{{--                    <th colspan="2" class="text-center">Supplier Info</th>--}}
{{--                </tr>--}}
{{--                <tr>--}}
{{--                    <th>Name</th>--}}
{{--                    <td>{{ $order->supplier->name }}</td>--}}
{{--                </tr>--}}
{{--                <tr>--}}
{{--                    <th>Mobile</th>--}}
{{--                    <td>{{ $order->supplier->mobile }}</td>--}}
{{--                </tr>--}}
{{--                <tr>--}}
{{--                    <th>Address</th>--}}
{{--                    <td>{{ $order->supplier->address }}</td>--}}
{{--                </tr>--}}
{{--            </table>--}}
{{--        </div>--}}
    </div>


    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>Product Type</th>
                        <th>Product Brand</th>
                        <th>Product Color</th>
                        <th>Product Name</th>
                        <th>IMEI/Barcode No.</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Selling Price</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($order->PurchaseInventory as $product)
                        <tr>
                            <td>{{$product->productType->name}}</td>
                            <td>{{$product->brand->name}}</td>
                            <td>{{$product->color->name??''}}</td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->serial_no??'' }}</td>
                            <td>{{ $product->quantity}}</td>
                            <td>৳{{ number_format($product->unit_price, 2) }}</td>
                            <td>৳{{ number_format($product->selling_price, 2) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

{{--    <div class="row">--}}
{{--        <div class="col-xs-offset-6 col-xs-6">--}}
{{--            <table class="table table-bordered">--}}
{{--                <tr>--}}
{{--                    <th>Total Amount</th>--}}
{{--                    <td >৳{{ number_format($order->sub_total, 2) }}</td>--}}
{{--                </tr>--}}
{{--                <tr>--}}
{{--                    <th>Discount Amount</th>--}}
{{--                    <td>৳{{ number_format(($order->sub_total-$order->total), 2) }}</td>--}}
{{--                </tr>--}}
{{--                <tr>--}}
{{--                    <th>Payable Total Amount</th>--}}
{{--                    <td>৳{{ number_format($order->total, 2) }}</td>--}}
{{--                </tr>--}}
{{--                <tr>--}}
{{--                    <th>Paid</th>--}}
{{--                    <td>৳{{ number_format($order->paid, 2) }}</td>--}}
{{--                </tr>--}}
{{--                <tr>--}}
{{--                    <th>Due</th>--}}
{{--                    <td>৳{{ number_format($order->due, 2) }}</td>--}}
{{--                </tr>--}}
{{--            </table>--}}
{{--        </div>--}}
{{--    </div>--}}
</div>


<script>
    window.print();
    window.onafterprint = function(){ window.close()};
</script>
</body>
</html>
