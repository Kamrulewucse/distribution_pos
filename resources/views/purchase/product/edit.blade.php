@extends('layouts.app')

@section('title')
    Product Model Edit
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Product Model Information</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form class="form-horizontal" method="POST" action="{{ route('purchase_product.edit', ['productModel' => $productModel->id]) }}">
                    @csrf

                    <div class="box-body">
                        <div class="form-group {{ $errors->has('product_type') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Product Type *</label>

                            <div class="col-sm-10">
                                <select class="form-control" name="product_type" id="product_type">
                                    <option value="">Select Product Type</option>
                                    @foreach($productTypes as $productType)
                                        <option value="{{$productType->id}}" {{ ($productType->id == $productModel->product_type_id)?'selected':''}}>{{$productType->name}}</option>
                                    @endforeach
                                </select>

                                @error('product_type')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('product_brand') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Product Brand *</label>

                            <div class="col-sm-10">
                                <select class="form-control" name="product_brand" id="brand">
                                    <option value="">Select Brand</option>
                                </select>

                                @error('product_brand')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('name') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Model Name *</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter Name"
                                       name="name" value="{{ empty(old('name')) ? ($errors->has('name') ? '' : $productModel->name) : old('name') }}">
                                @error('name')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('purchase_price') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Purchase Price *</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter Purchase Price"
                                       name="purchase_price" value="{{ empty(old('purchase_price')) ? ($errors->has('purchase_price') ? '' : $productModel->purchase_price) : old('purchase_price') }}">
                                @error('purchase_price')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('selling_price') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Selling Price *</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter Selling Price"
                                       name="selling_price" value="{{ empty(old('selling_price')) ? ($errors->has('selling_price') ? '' : $productModel->selling_price) : old('selling_price') }}">
                                @error('selling_price')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('model_discount') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Discount Amount</label>
                            <div class="col-sm-10">
                                <input type="text" value="{{ old('profit_in_percentage',$productModel->model_discount??'') }}" name="model_discount" class="form-control" placeholder="Enter Discount">
                                @error('model_discount')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('code') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Code</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter Code"
                                       name="code" value="{{ empty(old('code')) ? ($errors->has('code') ? '' : $productModel->code) : old('code') }}" readonly>

                                @error('code')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>


                        <div class="form-group {{ $errors->has('status') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Status *</label>

                            <div class="col-sm-10">

                                <div class="radio" style="display: inline">
                                    <label>
                                        <input type="radio" name="status" value="1" {{ empty(old('status')) ? ($errors->has('status') ? '' : ($productModel->status == '1' ? 'checked' : '')) :
                                            (old('status') == '1' ? 'checked' : '') }}>
                                        Active
                                    </label>
                                </div>

                                <div class="radio" style="display: inline">
                                    <label>
                                        <input type="radio" name="status" value="0" {{ empty(old('status')) ? ($errors->has('status') ? '' : ($productModel->status == '0' ? 'checked' : '')) :
                                            (old('status') == '0' ? 'checked' : '') }}>
                                        Inactive
                                    </label>
                                </div>

                                @error('status')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->

                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(function () {
            //Color hide
            $('#product_type').change(function (){
                var productType = $(this).val();
                if (productType != '3'){
                    $("#color_hide").show();
                }else{
                    $("#color_hide").hide();
                }
            });
            $('#product_type').trigger("change");

            var productTypeSelected = '{{ empty(old('product_type')) ? ($errors->has('product_type') ? '' : $productModel->product_brand_id) : old('product_type') }}';

            $('#product_type').change(function () {
                var productTypeID = $(this).val();

                $('#brand').html('<option value="">Select Brand</option>');

                if ( productTypeID != '') {
                    $.ajax({
                        method: "GET",
                        url: "{{ route('get_subCategory') }}",
                        data: {  productTypeID:  productTypeID }
                    }).done(function( data ) {
                        $.each(data, function( index, item ) {
                            if (productTypeSelected == item.id)
                                $('#brand').append('<option value="'+item.id+'" selected>'+item.name+'</option>');
                            else
                                $('#brand').append('<option value="'+item.id+'">'+item.name+'</option>');
                        });

                        $('#subcategory').trigger('change');
                    });
                }
            });

            $('#product_type').trigger('change');
        });
    </script>
@endsection
