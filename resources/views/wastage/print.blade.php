<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!--Favicon-->
    <link rel="icon" href="{{ asset('img/favicon.ico') }}" type="image/x-icon" />

    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">

    <style>
        @page {
            @top-center {
                content: element(pageHeader);
            }
        }
        #pageHeader{
            position: running(pageHeader);
        }

        table.table-bordered{
            border:1px solid black !important;
            margin-top:20px;
        }
        table.table-bordered th{
            border:1px solid black !important;
        }
        table.table-bordered td{
            border:1px solid black !important;
        }

        .product-table th, .table-summary th {
            padding: 2px !important;
            text-align: center !important;
        }

        .product-table td, .table-summary td {
            padding: 2px !important;
            text-align: center !important;
        }
        .main_section{
            width: 520px;
            height: auto;
            margin: 0 auto;
        }

        @media screen {
            div.divFooter {
                display: none;
            }
        }
        @media print {
            div.divFooter {
                position: fixed;
                bottom: 0;
            }
        }
        .row {
            margin-right: 0px;
            margin-left: 0px;
        }

        @page {
            size: 3in;
        }
        .container-fluid{
            padding: 0 2px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-4">
{{--                <img src="{{ asset('img/logo.png') }}" width="100px" height="80px">--}}
            </div>
            <div class="col-md-8">
                <p style="font-family: 'Times New Roman'; font-size: 33px; font-style: italic; margin: 0px; font-weight: bold">{{ config('app.name') }}</p>
            </div>

            <div class="col-xs-12">

                <p style="margin: 0px">
                    SP Market, Nilsagor Road <br>
                    Vobanigon Bazar, Sadar, Nilphamari <br>
                    PHONE - 01713937677<br>
                    EMAIL - stylebazar@yahoo.com
                </p>

            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <table class="table table-bordered">
                    <tr>
                        <th> Wastag ID.</th>
                        <td>{{ $wastage->id }}</td>
                    </tr>
                    <tr>
                        <th>Wastage Date</th>
                        <td>{{ $wastage->date->format('j F, Y') }}</td>
                    </tr>
                    {{-- <tr>
                        <th>Received By</th>
                        <td>{{ $wastage->user->name??'' }}</td>
                    </tr> --}}
                </table>
            </div>

        </div>

        @if(count($wastage->products) > 0)
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Category</th>
                            <th>Sub Category</th>
                            <th>Product Name</th>
                            <th>Serial</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                            <th>Total</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($wastage->products as $product)
                            <tr>
                                <td>{{ $product->purchase_product_category_name }}</td>
                                <td>{{ $product->purchase_product_sub_category_name }}</td>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->serial }}</td>
                                <td>{{ $product->quantity.' '.$product->unit }}</td>
                                <td>৳{{ number_format($product->unit_price, 2) }}</td>
                                <td>৳{{ number_format($product->total, 2) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>

<script>
    window.print();
    window.onafterprint = function(){ window.close()};
</script>
</body>
</html>
