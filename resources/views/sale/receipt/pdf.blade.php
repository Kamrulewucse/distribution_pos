<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Bootstrap 3.3.7 -->

    <style>
        .table {
            width: 100%;
            max-width: 100%;
            margin-bottom: 20px;
        }
        table {
            background-color: transparent;
        }
        .table-bordered {
            border: 1px solid #f4f4f4;
        }
        tbody {
            display: table-row-group;
            vertical-align: middle;
            border-color: inherit;
        }
        table {
            border-collapse: collapse;
            border-spacing: 0;
        }
        .table-bordered>thead>tr>th, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>tbody>tr>td, .table-bordered>tfoot>tr>td {
            border: 1px solid #f4f4f4;
        }
        .table-bordered>thead>tr>th, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>tbody>tr>td, .table-bordered>tfoot>tr>td {
            border: 1px solid #f4f4f4;
        }
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
            margin:5px;
            size: 80mm 500px;
            -webkit-print-color-adjust: exact !important;
            color-adjust: exact !important;

        }
        .container-fluid{
            padding: 0 2px;
        }
        .body-table>tbody>tr>td, .body-table>tbody>tr>th, .body-table>tfoot>tr>td, .body-table>tfoot>tr>th, .body-table>thead>tr>td, .body-table>thead>tr>th {
            padding: 0;
        }
        body {
            -webkit-print-color-adjust: exact !important;
            color-adjust: exact !important;
        }
    </style>
</head>
<body>
  <div class="container-fluid">
            <div class="row">
                <div class="col-xs-10">
                    <p style="font-family: 'Times New Roman'; font-size: 20px; font-style: italic; margin: 0px; font-weight: bold">{{ config('app.name') }}</p>
                </div>

                <div class="col-xs-12">
                    <p style="margin: 0px;font-size: 9px">
                        Address, Dhaka<br>
                        PHONE - +8801XX-XXXXXX<br>
                        EMAIL - example@gmail.com
                    </p>

                </div>
            </div>
     <div class="row">
        <div class="col-xs-12 text-center" style="font-size: 16px;text-align: center">
            <strong>Invoice</strong>
        </div>
    </div>
     <div class="row" style="clear: both;border: 1px solid black!important; margin-top: 3px; font-size: 12px;">
        <div class="col-xs-12" style="clear: both">
            <div class="row" style="width: 100%;clear: both;min-height: 30px">
                @if($order->customer_id)
                <div class="col-xs-6" style="padding:0 !important;width: 50%;float: left;font-size: 8px">
                    <strong>Retailer Name: </strong>{{ $order->customer->name }} <br>
                    <strong>Address: </strong>{{ $order->customer->address }} <br>
                    <strong>Mobile No. : </strong>{{ $order->customer->mobile_no }} <br>
                </div>
                @endif

                <div class="col-xs-6" style="padding:0 !important;width: 50%;float: left;font-size: 8px">
                    <strong>Invoice No : </strong>{{ $order->order_no }} <br>
                    <strong>Date : </strong>{{ $order->date->format('d/m/Y') }}
                </div>
            </div>
        </div>
    </div>
    <div class="row" style="clear: both">
        <div class="col-xs-12" style="padding:0 !important;">
            @if(count($order->products) > 0)
                <table class="table table-bordered body-table  text-center" style="margin-top: 5px; margin-bottom: 1px !important; font-size: 7px;">
            <thead>
            <tr>
                <th style="background-color: lightgrey !important;">#</th>
                <th style="background-color: lightgrey !important;"> Brand</th>
                <th style="background-color: lightgrey !important;">Model</th>
                <th style="background-color: lightgrey !important;">Color</th>
                <th style="background-color: lightgrey !important;">IMEI</th>
                <th style="background-color: lightgrey !important;">Qty</th>
                <th style="background-color: lightgrey !important;"> Price</th>
                <th style="background-color: lightgrey !important;">Amount</th>
            </tr>
            </thead>

            <tbody>
            @foreach($order->products as $product)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $product->brand->name ?? '' }}</td>
                    <td>{{ $product->pivot->name }}</td>
                    <td>{{  getProductColor($product->pivot->product_color_id)->name ?? '' }}</td>
                    <td>{{ $product->pivot->serial }}</td>
                    <td>{{  round($product->pivot->quantity).' '.$product->pivot->unit }}</td>
                    <td>{{ number_format($product->pivot->unit_price, 2) }}</td>
                    <td>{{ number_format($product->pivot->total, 2) }}</td>
                </tr>
            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <th colspan="5" style="text-align: right !important;padding-right: 2px;">Total</th>
                <th colspan="1" style="text-align: center !important;" class="text-center">
                    {{ number_format($order->products->sum('pivot.quantity')) }}
                </th>
                <th colspan="2" style="text-align: right !important;">{{ number_format($order->sub_total, 2) }}</th>
            </tr>
            @if($order->vat >0)
                <tr>
                    <th class="text-right" style="text-align: right !important;" colspan="6">Vat {{ number_format($order->vat_percentage,2) }}%</th>
                    <th colspan="2" style="text-align: right !important;" class="text-right">
                        {{ number_format($order->vat, 2) }}
                    </th>
                </tr>
            @endif
            @if($order->discount > 0)
            <tr>
                <th class="text-right" style="text-align: right !important;" colspan="6">Discount {{ number_format($order->discount,2) }}%</th>
                <th colspan="2" style="text-align: right !important;" class="text-right">
                    {{ number_format(($order->discount / 100) * $order->sub_total, 2) }}
                </th>
            </tr>
            @endif
            <tr>
                <th class="text-right" style="text-align: right !important;" colspan="6">Net Total</th>
                <th colspan="2" style="text-align: right !important;" class="text-right">{{ number_format($order->total, 2) }}</th>
            </tr>
            </tfoot>
        </table>
                @if($order->user)
                    <p style="font-size: 11px">SR Name: {{ $order->user->name ?? '' }}</p>
                @endif
            @endif
        </div>
    <div class="col-xs-12" style="padding:0 !important">
    <div class="" style="clear: both;font-size: 9px">
        @if($order->vat > 0)
            VAT Money was given to the Retailer. <br>
        @endif
        <strong>In Word: {{ $order->amount_in_word }} Only</strong>
     </div>
    </div>
    </div>
  </div>

</body>
</html>
