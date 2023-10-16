@extends('layouts.app')

@section('style')

    <style>
        .table-bordered>thead>tr>th, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>tbody>tr>td, .table-bordered>tfoot>tr>td {
            white-space: nowrap;
        }

        input.form-control.quantity {
            width: 150px;
        }

        .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
            vertical-align: middle;
        }

    </style>

@endsection

@section('title')
    SR Product Assign
@endsection

@section('content')
    @if(Session::has('message'))
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            {{ Session::get('message') }}
        </div>
    @endif
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Product Assign Information</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form method="POST" action="{{ route('sr_product_assign.create') }}">
                    @csrf

                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group {{ $errors->has('sr') ? 'has-error' :'' }}">
                                    <label>SR *</label>
                                    <select class="form-control select2 sr" required id="sr" style="width: 100%;" name="sr" data-placeholder="Select SR">
                                        <option value="">Select SR</option>
                                        @foreach($srs as $sr)
                                            <option value="{{ $sr->id }}" {{ old('sr') == $sr->id ? 'selected' : '' }}>{{ $sr->name }}</option>
                                        @endforeach
                                    </select>

                                    @error('sr')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group {{ $errors->has('date') ? 'has-error' :'' }}">
                                    <label>Date *</label>

                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" required class="form-control pull-right date-picker" id="date" name="date" value="{{ empty(old('date')) ? ($errors->has('date') ? '' : date('Y-m-d')) : old('date') }}" autocomplete="off">
                                    </div>
                                    <!-- /.input group -->

                                    @error('date')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th width="40%">Brand</th>
                                        <th width="50%">Brand Model</th>
                                        <th width="10%">Quantity</th>
                                        <th></th>
                                    </tr>
                                </thead>

                                <tbody id="product-container">
                                @if (old('product_brand') != null && sizeof(old('product_brand')) > 0)
                                    @foreach(old('product_brand') as $item)
                                        <tr class="product-item">

                                            <td>
                                                <div class="form-group {{ $errors->has('product_brand.'.$loop->index) ? 'has-error' :'' }}">
                                                    <select  class="form-control product_brand select2" data-selected-brand="{{ old('product_brand.'.$loop->index) }}" name="product_brand[]" required>
                                                        <option value="">Select Product Brand</option>
                                                    </select>
                                                </div>
                                            </td>
                                            <td >
                                                <div class="form-group {{ $errors->has('product_model.'.$loop->index) ? 'has-error' :'' }}">
                                                    <select class="form-control product_model select2" data-selected-product-model="{{ old('product_model.'.$loop->index) }}" name="product_model[]" required>
                                                        <option value="">Select Product Model</option>
                                                    </select>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group {{ $errors->has('quantity.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="text" step="any" class="form-control quantity" name="quantity[]" value="{{ old('quantity.'.$loop->index) }}" required>
                                                </div>
                                            </td>
                                            <td  class="text-center">
                                                <a role="button" class="btn btn-danger btn-sm btn-remove">X</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr class="product-item">
                                        <td>
                                            <div class="form-group">
                                                <select class="form-control product_brand select2" name="product_brand[]" required>
                                                    <option value="">Select Brand</option>
                                                </select>
                                            </div>
                                        </td>
                                        <td >
                                            <div class="form-group">
                                                <select class="form-control product_model select2" name="product_model[]" required>
                                                    <option value="">Select Product Model</option>
                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input type="text" required step="any" class="form-control quantity" name="quantity[]">
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <a role="button" class="btn btn-danger btn-sm btn-remove">X</a>
                                        </td>
                                    </tr>
                                @endif
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td>
                                            <a role="button" class="btn btn-info btn-sm" id="btn-add-product">Add Product</a>
                                        </td>
                                        <th colspan="2" class="text-right">Total Quantity</th>
                                        <th id="total-amount">৳0.00</th>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
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

    <template id="template-product">
        <tr class="product-item">
            <td>
                <div class="form-group">
                    <select class="form-control product_brand select2" style="width: 100%;" name="product_brand[]" required>
                        <option value="">Select Brand</option>

                    </select>
                </div>
            </td>

            <td>
                <div class="form-group">
                    <select class="form-control product_model select2" style="width: 100%;" name="product_model[]" required>
                        <option value="">Select Product Model</option>
                    </select>
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="text" step="any" class="form-control quantity" required name="quantity[]">
                </div>
            </td>
            <td  class="text-center">
                <a role="button" class="btn btn-danger btn-sm btn-remove">X</a>
            </td>
        </tr>
    </template>
@endsection

@section('script')

    <script>
        $(function () {

            $('body').on('change','#sr', function () {
                var srId = $(this).val();

                $('.product_brand').html('<option value="">Select Brand</option>');
                $('.product_model').html('<option value="">Select Product Model</option>');

                if (srId != '') {

                    $.ajax({
                        method: "GET",
                        url: "{{ route('get_brands') }}",
                        data: { srId: srId }
                    }).done(function( data ) {
                        $.each(data, function( index, item ) {
                            $('.product_brand').append('<option value="'+item.id+'">'+item.name+'</option>');
                        });
                        $('.product_brand').trigger('change');
                    });
                }

            });
            $('#sr').trigger('change');
            // select Product Model
            $('body').on('change','.product_brand', function () {
                var productBrandId = $(this).val();
                var itemProductBrand = $(this);
                var productType = itemProductBrand.closest('tr').find('.product_type').val();
                itemProductBrand.closest('tr').find('.product_model').html('<option value="">Select Product Model</option>');
                var productSelected = itemProductBrand.closest('tr').find('.product_model').attr("data-selected-product-model");

                if (productBrandId != '') {
                    $.ajax({
                        method: "GET",
                        url: "{{ route('get_product_models') }}",
                        data: { productBrandId: productBrandId, productType:productType }
                    }).done(function( data ) {
                        $.each(data, function( index, item ) {
                            if(productSelected == item.id){
                                itemProductBrand.closest('tr').find('.product_model').append('<option value="'+item.id+'" selected>'+item.name+'</option>');
                            }else{
                              itemProductBrand.closest('tr').find('.product_model').append('<option value="'+item.id+'">'+item.name+'</option>');
                          }
                        });
                    });
                }

            });
            $('.product_brand').trigger('change');

            $('#btn-add-product').click(function () {
                var html = $('#template-product').html();
                var item = $(html);
                $('#product-container').append(item);

                var srId = $('#sr').val();
                if (srId != '') {

                    $.ajax({
                        method: "GET",
                        url: "{{ route('get_brands') }}",
                        data: { srId: srId }
                    }).done(function( data ) {
                        $.each(data, function( index, item ) {
                            $('.product_brand').last().append('<option value="'+item.id+'">'+item.name+'</option>');
                        });
                        $('.product_brand').last().trigger('change');
                    });
                }

                initProduct();

                if ($('.product-item').length >= 1 ) {
                    $('.btn-remove').show();
                }
            });

            $('body').on('click', '.btn-remove', function () {
                $(this).closest('.product-item').remove();
                calculate();

                if ($('.product-item').length <= 1 ) {
                    $('.btn-remove').hide();
                }
            });

            $('body').on('keyup', '.quantity, .unit_price', function () {
                calculate();
            });

            if ($('.product-item').length <= 1 ) {
                $('.btn-remove').hide();
            } else {
                $('.btn-remove').show();
            }
            initProduct();
            calculate();
        });

        function calculate() {
            var total = 0;
            $('.product-item').each(function(i, obj) {
                var quantity = $('.quantity:eq('+i+')').val();
                var unit_price = $('.unit_price:eq('+i+')').val();

                if (quantity == '' || quantity < 0 || !$.isNumeric(quantity))
                    quantity = 0;

                if (unit_price == '' || unit_price < 0 || !$.isNumeric(unit_price))
                    unit_price = 0;

                $('.total-cost:eq('+i+')').html('৳' + (quantity * unit_price).toFixed(2) );
                total += quantity * unit_price;
            });

            $('#total-amount').html('৳' + total.toFixed(2));
        }

        function initProduct() {
            $('.select2').select2();
        }

        $(document).ready(function() {
            $(window).keydown(function(event){
                if(event.keyCode == 13) {
                    event.preventDefault();
                    return true;
                }
            });
        });
    </script>
@endsection
