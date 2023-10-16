@extends('layouts.app')

@section('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endsection

@section('title')
    Wastage Details
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12 text-right">
                            <a target="_blank" href="{{ route('wastage.print', $wastage->id) }}" class="btn btn-primary">Print</a>
                        </div>
                    </div>

                    <hr>

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
            </div>
        </div>
    </div>
@endsection

@section('script')
    <!-- DataTables -->
    <script src="{{ asset('themes/backend/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('themes/backend/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>

    <script>
        $(function () {
            $('#table-payments').DataTable({
                "order": [[ 0, "desc" ]],
            });
        });
    </script>
@endsection
