@extends('layouts.app')

@section('style')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/select2/dist/css/select2.min.css') }}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
@endsection

@section('title')
    Purchase Order
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Order Information</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form method="POST" action="{{ route('purchase_order.create') }}">
                    @csrf

                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group {{ $errors->has('supplier') ? 'has-error' :'' }}">
                                    <label>Supplier</label>

                                    <select class="form-control select2 supplier" style="width: 100%;" name="supplier" data-placeholder="Select Supplier">
                                        <option value="">Select Supplier</option>

                                        @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}" {{ old('supplier') == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                                        @endforeach
                                    </select>

                                    @error('supplier')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group {{ $errors->has('warehouse') ? 'has-error' :'' }}">
                                    <label>Warehouse</label>

                                    <select class="form-control select2 warehouse" style="width: 100%;" name="warehouse" data-placeholder="Select Warehouse">
                                        <option value="">Select Warehouse</option>

                                        @foreach($warehouses as $warehouse)
                                            <option value="{{ $warehouse->id }}" {{ old('warehouse') == $warehouse->id ? 'selected' : '' }}>{{ $warehouse->name }}</option>
                                        @endforeach
                                    </select>

                                    @error('warehouse')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group {{ $errors->has('date') ? 'has-error' :'' }}">
                                    <label>Date</label>

                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control pull-right" id="date" name="date" value="{{ empty(old('date')) ? ($errors->has('date') ? '' : date('Y-m-d')) : old('date') }}" autocomplete="off">
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
                                        <th width="10%"> Category</th>
                                        <th width="10%"> Sub Category</th>
                                        <th width="10%">Product Name</th>
                                        <th width="10%">Type</th>
                                        <th width="10%">Serial Number</th>
                                        <th width="10%">Quantity</th>
                                        <th width="10%">Unit Price</th>
                                        <th width="10%">Selling Price</th>
                                        <th width="10%">Total Cost</th>
                                        <th width="10%"></th>
                                    </tr>
                                </thead>

                                <tbody id="product-container">
                                @if (old('category') != null && sizeof(old('category')) > 0)
                                    @foreach(old('category') as $item)
                                        <tr class="product-item">
                                            <td width="10%">
                                                <div class="form-group {{ $errors->has('category.'.$loop->index) ? 'has-error' :'' }}">
                                                    <select class="form-control category" style="width: 100%;" name="category[]" required>
                                                        <option value="">Select Category</option>
                                                        @foreach($categories as $category)
                                                            <option value="{{ $category->id }}" {{ old('category.'.$loop->parent->index) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </td>
                                            <td width="10%">
                                                <div class="form-group {{ $errors->has('subcategory.'.$loop->index) ? 'has-error' :'' }}">
                                                    <select class="form-control subcategory" style="width: 100%;" data-selected-subcategory="{{ old('subcategory.'.$loop->index) }}" name="subcategory[]">
                                                        <option value="">Select Sub Category</option>
                                                    </select>
                                                </div>
                                            </td>
                                            <td width="10%">
                                                <div class="form-group {{ $errors->has('product.'.$loop->index) ? 'has-error' :'' }}">
                                                    <select class="form-control product" style="width: 100%;" data-selected-product="{{ old('product.'.$loop->index) }}" name="product[]">
                                                        <option value="">Select Product</option>
                                                    </select>
                                                </div>
                                            </td>

                                            <td width="10%">
                                                <select class="form-control {{ $errors->has('type.'.$loop->index) ? 'has-error' :'' }} type" name="type[]">
                                                    <option value="2">Multiple</option>
                                                    <option value="1" {{ old('type.'.$loop->index) == '1' ? 'selected' : '' }}>Single</option>
                                                </select>
                                            </td>

                                            <td width="10%">
                                                <div class="form-group {{ $errors->has('serial.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="text" class="form-control serial" name="serial[]" value="{{ old('serial.'.$loop->index) }}">
                                                </div>
                                            </td>
                                            <td width="10%">
                                                <div class="form-group {{ $errors->has('quantity.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="number" step="any" class="form-control quantity" name="quantity[]" value="{{ old('quantity.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td width="10%">
                                                <div class="form-group {{ $errors->has('unit_price.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="text" class="form-control unit_price" name="unit_price[]" value="{{ old('unit_price.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td width="10%">
                                                <div class="form-group {{ $errors->has('selling_price.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="text" class="form-control selling_price" name="selling_price[]" value="{{ old('selling_price.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td width="10%" class="total-cost">৳0.00</td>
                                            <td width="10%" class="text-center">
                                                <a role="button" class="btn btn-danger btn-sm btn-remove">X</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr class="product-item">
                                        <td width="10%">
                                            <div class="form-group">
                                                <select class="form-control category" style="width: 100%;" name="category[]" required>
                                                    <option value="">Select Category</option>
                                                    @foreach($categories as $category)
                                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </td>
                                        <td width="10%">
                                            <div class="form-group">
                                                <select class="form-control subcategory" style="width: 100%;" name="subcategory[]">
                                                    <option value="">Select Sub Category</option>
                                                </select>
                                            </div>
                                        </td>
                                        <td width="10%">
                                            <div class="form-group">
                                                <select class="form-control product" style="width: 100%;" name="product[]">
                                                    <option value="">Select Product</option>
                                                </select>
                                            </div>
                                        </td>
                                        <td width="10%">
                                            <select class="form-control type" name="type[]">
                                                <option value="2">Multiple</option>
                                                <option value="1">Single</option>
                                            </select>
                                        </td>
                                        <td width="10%">
                                            <div class="form-group">
                                                <input type="text" class="form-control serial" name="serial[]" value="BP{{ rand(10000, 99999) }}">
                                            </div>
                                        </td>
                                        <td width="10%">
                                            <div class="form-group">
                                                <input type="number" step="any" class="form-control quantity" name="quantity[]">
                                            </div>
                                        </td>
                                        <td width="10%">
                                            <div class="form-group">
                                                <input type="text" class="form-control unit_price" name="unit_price[]">
                                            </div>
                                        </td>
                                        <td width="10%">
                                            <div class="form-group">
                                                <input type="text" class="form-control selling_price" name="selling_price[]">
                                            </div>
                                        </td>

                                        <td width="10%" class="total-cost">৳0.00</td>
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
                                        <th colspan="7" class="text-right">Total Amount</th>
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
        @php
            $i=1;
        @endphp
        <tr class="product-item">
            <td width="10%">
                <div class="form-group">
                    <select class="form-control category" style="width: 100%;" name="category[]" required>
                        <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                    </select>
                </div>
            </td>
            <td width="10%">
                <div class="form-group">
                    <select class="form-control subcategory" style="width: 100%;" name="subcategory[]">
                        <option value="">Select Sub Category</option>
                    </select>
                </div>
            </td>
            <td width="10%">
                <div class="form-group">
                    <select class="form-control product" style="width: 100%;" name="product[]">
                        <option value="">Select Product</option>
                    </select>
                </div>
            </td>
            <td width="10%">
                <select class="form-control type" name="type[]">
                    <option value="2">Multiple</option>
                    <option value="1">Single</option>
                </select>
            </td>
            <td width="10%">
                <div class="form-group">
                    <input type="text" class="form-control serial" name="serial[]">
                </div>
            </td>

            <td width="10%">
                <div class="form-group">
                    <input type="number" step="any" class="form-control quantity" name="quantity[]">
                </div>
            </td>

            <td width="10%">
                <div class="form-group">
                    <input type="text" class="form-control unit_price" name="unit_price[]">
                </div>
            </td>
            <td width="10%">
                <div class="form-group">
                    <input type="text" class="form-control selling_price" name="selling_price[]">
                </div>
            </td>

            <td width="10%" class="total-cost">৳0.00</td>
            <td width="10%" class="text-center">
                <a role="button" class="btn btn-danger btn-sm btn-remove">X</a>
            </td>
        </tr>
    </template>
@endsection

@section('script')
    <!-- Select2 -->
    <script src="{{ asset('themes/backend/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
    <!-- bootstrap datepicker -->
    <script src="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <script>
        $(function () {
            //Initialize Select2 Elements
            $('.product').select2();
            $('.category').select2();
            $('.subcategory').select2();
            $('.supplier').select2();
            $('.warehouse').select2();


            // select Category


            $('body').on('change','.category', function () {
                var categoryID = $(this).val();
                var itemCategory = $(this);
                itemCategory.closest('tr').find('.subcategory').html('<option value="">Select Sub Category</option>');
                var index = $('.category').index(this);
                var selected = itemCategory.closest('tr').find('.subcategory').attr("data-selected-subcategory");

                if (categoryID != '') {
                    $.ajax({
                        method: "GET",
                        url: "{{ route('get_subCategory') }}",
                        data: { categoryID: categoryID }
                    }).done(function( data ) {
                        $.each(data, function( index, item ) {
                            if (selected == item.id)
                                itemCategory.closest('tr').find('.subcategory').append('<option value="'+item.id+'" selected>'+item.name+'</option>');
                            else
                                itemCategory.closest('tr').find('.subcategory').append('<option value="'+item.id+'">'+item.name+'</option>');
                        });
                        itemCategory.closest('tr').find('.subcategory').trigger('change');
                    });
                    //itemCategory.closest('tr').find('.subcategory').trigger('change');
                }

            });
            $('.category').trigger('change');

            // select Sub Category
            $('body').on('change','.subcategory', function () {
                var subcategoryID = $(this).val();
                var itemSubCategory = $(this);
                itemSubCategory.closest('tr').find('.product').html('<option value="">Select Product</option>');
                var index = $(".subcategory").index(this);
                var selected = itemSubCategory.closest('tr').find('.product').attr("data-selected-product");

                if (subcategoryID != '') {
                    $.ajax({
                        method: "GET",
                        url: "{{ route('get_product') }}",
                        data: { subcategoryID: subcategoryID }
                    }).done(function( data ) {

                        $.each(data, function( index, item ) {
                            if (selected == item.id)
                                itemSubCategory.closest('tr').find('.product').append('<option value="'+item.id+'" selected>'+item.name+'</option>');
                            else
                                itemSubCategory.closest('tr').find('.product').append('<option value="'+item.id+'">'+item.name+'</option>');
                        });

                        //itemSubCategory.closest('tr').find('.product').trigger('change');
                    });
                }

            });





            //Date picker
            $('#date').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd'
            });

            $('#btn-add-product').click(function () {
                var html = $('#template-product').html();
                var item = $(html);

                item.find('.serial').val('BP' + Math.floor((Math.random() * 100000)));
                $('#product-container').append(item);

                initProduct();

                if ($('.product-item').length >= 1 ) {
                    $('.btn-remove').show();
                }

                $('.type').trigger('change');
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

            $('body').on('change', '.type', function () {
                var type = $(this).val();
                var count = $(this).closest('tr').find('.quantity').val();
                var categoryId = $(this).closest('tr').find('.category').val();
                var subCategoryId = $(this).closest('tr').find('.subcategory').val();
                var productId = $(this).closest('tr').find('.product').val();

                var unitPrice = $(this).closest('tr').find('.unit_price').val();
                var sellingPrice = $(this).closest('tr').find('.selling_price').val();

                if (type == '1') {
                    $(this).closest('tr').find('.quantity').val('1');
                    $(this).closest('tr').find('.quantity').prop('readonly', true);

                    if (count > 1) {
                        for(i=1; i<count; i++) {
                            var html = $('#template-product').html();
                            var item = $(html);
                            item.find('.category').val(categoryId);
                            item.find('.serial').val('BP' + Math.floor((Math.random() * 100000)));
                            item.find('.type').val('1');
                            item.find('.unit_price').val(unitPrice);
                            item.find('.selling_price').val(sellingPrice);

                            $('#product-container').append(item);
                            item.find('.category').trigger('change');
                            item.find('.subcategory').change(function (){
                               $(this).val(subCategoryId);
                               $('.product').val(productId).change();
                            });


                            initProduct();
                        }
                        $('.type').trigger('change');

                        calculate();
                    }
                } else {
                    $(this).closest('tr').find('.quantity').prop('readonly', false);
                }
            });

            $('.type').trigger('change');


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
            $('.product').select2();
            $('.category').select2();
            $('.subcategory').select2();
        }

        $(document).ready(function() {
            $(window).keydown(function(event){
                if(event.keyCode == 13) {
                    event.preventDefault();
                    return false;
                }
            });
        });



    </script>
@endsection
