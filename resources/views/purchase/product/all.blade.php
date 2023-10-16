@extends('layouts.app')
@section('title')
    Product Model No
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
                    <a class="btn btn-primary" href="{{ route('purchase_product.add') }}">Add Product Model No</a>

                    <hr>

                    <table id="table" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Brand</th>
                            <th>Product Type</th>
                            <th>purchase Price</th>
                            <th>Selling Price</th>
                            <th>Discount(amount)</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($products as $product)
                            <tr>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->brand->name??'' }}</td>
                                <td>{{ $product->productType->name??'' }}</td>
                                <td>{{ $product->purchase_price }}</td>
                                <td>{{ $product->selling_price }}</td>
                                <td>৳ {{ $product->model_discount }}</td>
                                <td>
                                    @if ($product->status == 1)
                                        <span class="label label-success">Active</span>
                                    @else
                                        <span class="label label-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <a class="btn btn-info btn-sm" href="{{ route('purchase_product.edit', ['productModel' => $product->id]) }}">Edit</a>
                                    <a role="button" class="btn btn-warning btn-sm" onclick="priceUpdate({{$product->id }},{{$product->purchase_price}},{{$product->selling_price}})">Price Update</a>

                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-edit">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"></span></button>
                    <h4 class="modal-title">Update Price</h4>
                </div>
                <div class="modal-body">
                    <form id="modal-edit-form" enctype="multipart/form-data" name="modal-edit-form">

                        <div class="form-group">
                            <input type="hidden" id="product_model_id" name="product_model_id">
                            <label>Purchase Price</label>
                            <input class="form-control" id="purchase_price" name="purchase_price" placeholder="Updated purchase Price">
                        </div>
                        <div class="form-group">
                            <label>Selling Price</label>
                            <input class="form-control" id="selling_price" name="selling_price" placeholder="Updated Selling Price">
                        </div>
                        <div class="form-group {{ $errors->has('date') ? 'has-error' :'' }}">
                            <label>Date</label>

                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control pull-right date-picker" id="date" name="date" value="{{ empty(old('date')) ? ($errors->has('date') ? '' : date('Y-m-d')) : old('date') }}" autocomplete="off">
                            </div>
                            <!-- /.input group -->

                            @error('date')
                            <span class="help-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="modal-btn-update">Save</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
@endsection

@section('script')

    <script>
        $(function () {
            $('#table').DataTable();

            $('#modal-btn-update').click(function () {
                var formData = new FormData($('#modal-edit-form')[0]);
                $.ajax({
                    type: "POST",
                    url: "{{ route('model_price.update')}}",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            $("#modal-edit").modal("hide");
                            Swal.fire(
                                'Updated!',
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
        })

        function priceUpdate(product_model_id,purchase_price,selling_price){
            $("#product_model_id").val(product_model_id);
            $("#purchase_price").val(purchase_price);
            $("#selling_price").val(selling_price);
            $("#modal-edit").modal("show");
        }
    </script>
@endsection
