@extends('layouts.app')

@section('style')
    <style>
        button.dce-btn-close {
            display: none;
        }
        button.close.dce-btn-close {
            display: block;
        }

        #UIElement {
            text-align: center;
            font-size: medium;
            height: 70vh;
            width: 100%;
        }

        #UIElement img {
            max-width: 100%;
            max-height: 90%;
            border: solid 1px gray;
        }


        select.dce-sel-camera {
            display: none !important;
        }

        select.dce-sel-resolution {
            display: none !important;
        }
        video {
            height: 421px!important;
            object-fit: none !important;
        }
        @media (max-width: 61.94em) {
            video {
                height: 352px !important;
                object-fit: none !important;
            }
        }
    </style>
@endsection

@section('title')
    Purchase Order
@endsection

@section('content')
    @if(Session::has('message'))
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            {{ Session::get('message') }}
        </div>
    @endif
<form method="POST" action="{{ route('purchase_order.create') }}">
        @csrf
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Order Information</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
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
                                        <input type="text" class="form-control pull-right date-picker" id="date" name="date" value="{{ empty(old('date')) ? ($errors->has('date') ? '' : date('Y-m-d')) : old('date') }}" autocomplete="off">
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
                                        <th>Product Type</th>
                                        <th>Brand</th>
                                        <th>Brand Model</th>
                                        <th>Color</th>
                                        <th>IMEI Code</th>
                                        <th>Quantity</th>
                                        <th>Unit Price</th>
                                        <th>Selling Price</th>
                                        <th>Total Cost</th>
                                        <th></th>
                                    </tr>
                                </thead>

                                <tbody id="product-container">
                                @if (old('product_type') != null && sizeof(old('product_type')) > 0)
                                    @foreach(old('product_type') as $item)
                                        <tr class="product-item">
                                            <td >
                                                <div class="form-group {{ $errors->has('product_type.'.$loop->index) ? 'has-error' :'' }}">
                                                        <select class="form-control product_type select2" style="width: 100%;" name="product_type[]" required>
                                                            <option value="">Select Type</option>
                                                            @foreach($productTypes as $productType)
                                                                <option value="{{$productType->id}}" {{ old('product_type.'.$loop->parent->index) ==$productType->id  ? 'selected' : '' }}>{{$productType->name}}</option>
                                                            @endforeach
                                                        </select>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group {{ $errors->has('product_brand.'.$loop->index) ? 'has-error' :'' }}">
                                                    <select class="form-control product_brand select2" data-selected-brand="{{ old('product_brand.'.$loop->index) }}" name="product_brand[]" required>
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
                                                <div class="form-group {{ $errors->has('product_color.'.$loop->index) ? 'has-error' :'' }}">
                                                    <select class="form-control product_color select2" name="product_color[]">
                                                        <option value="">Select Color</option>
                                                        @foreach($colors as $color)
                                                            <option value="{{$color->id}}" {{ old('product_color.'.$loop->parent->index) ==$color->id  ? 'selected' : '' }}>{{$color->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group {{ $errors->has('imei_code.'.$loop->index) ? 'has-error' :'' }}">
                                                    <textarea  style="display: none" class="form-control imei_code" name="imei_code[]" rows="2">{{ old('imei_code.'.$loop->index) }}</textarea>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group {{ $errors->has('quantity.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="text" step="any" class="form-control quantity" name="quantity[]" value="{{ old('quantity.'.$loop->index) }}">
                                                </div>
                                            </td>
{{--                                            <td  class="quantity">0.00</td>--}}

                                            <td>
                                                <div class="form-group {{ $errors->has('unit_price.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="text"  class="form-control unit_price" name="unit_price[]" value="{{ old('unit_price.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group {{ $errors->has('selling_price.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="text"  class="form-control selling_price" name="selling_price[]" value="{{ old('selling_price.'.$loop->index) }}">
                                                </div>
                                            </td>

                                            <td  class="total-cost">৳0.00</td>
                                            <td  class="text-center">
                                                <a role="button" class="btn btn-danger btn-sm btn-remove">X</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr class="product-item">
                                        <td>
                                            <div class="form-group">
                                                <select class="form-control product_type select2" name="product_type[]" required>
                                                    <option value="">Select Type</option>
                                                    @foreach($productTypes as $productType)
                                                        <option value="{{ $productType->id }}">{{ $productType->name }}</option>
                                                    @endforeach
                                                </select>

                                            </div>
                                        </td>
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
                                        <td >
                                            <div class="form-group">
                                                <select class="form-control product_color select2" name="product_color[]">
                                                    <option value="">Select Color</option>
                                                    @foreach($colors as $color)
                                                        <option value="{{ $color->id }}">{{ $color->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <textarea  style="display: none" class="form-control imei_code" name="imei_code[]" ></textarea>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input type="text" step="any" class="form-control quantity" name="quantity[]">
                                            </div>
                                        </td>

{{--                                        <td  class="quantity">0.00</td>--}}
                                        <td>
                                            <div class="form-group">
                                                <input type="text"  class="form-control unit_price" name="unit_price[]">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group ">
                                                <input type="text" class="form-control selling_price" name="selling_price[]">
                                            </div>
                                        </td>

                                        <td  class="total-cost">৳0.00</td>
                                        <td class="text-center">
                                            <a role="button" class="btn btn-danger btn-sm btn-remove">X</a>
                                        </td>
                                    </tr>
                                @endif
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="10">
                                            <a role="button" class="btn btn-info btn-sm" id="btn-add-product">Add Product</a>
                                        </td>
{{--                                        <th colspan="7" class="text-right">Total Amount</th>--}}
{{--                                        <th id="total-amount">৳0.00</th>--}}
{{--                                        <td></td>--}}
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <!-- /.box-body -->

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Payment</h3>
                </div>
                <!-- /.box-header -->

                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Payment Type</label>
                                <select class="form-control" id="modal-pay-type" name="payment_type">
                                    <option value="1" {{ old('payment_type') == '1' ? 'selected' : '' }}>Cash</option>
                                    <option value="2" {{ old('payment_type') == '2' ? 'selected' : '' }}>Bank</option>
                                    <option value="3" {{ old('payment_type') == '3' ? 'selected' : '' }}>Mobile Banking</option>
                                </select>
                            </div>

                            <div id="modal-bank-info">
                                <div class="form-group {{ $errors->has('bank') ? 'has-error' :'' }}">
                                    <label>Bank</label>
                                    <select class="form-control" id="modal-bank" name="bank">
                                        <option value="">Select Bank</option>

                                        @foreach($banks as $bank)
                                            <option value="{{ $bank->id }}" {{ old('bank') == $bank->id ? 'selected' : '' }}>{{ $bank->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group {{ $errors->has('branch') ? 'has-error' :'' }}">
                                    <label>Branch</label>
                                    <select class="form-control" id="modal-branch" name="branch">
                                        <option value="">Select Branch</option>
                                    </select>
                                </div>

                                <div class="form-group {{ $errors->has('account') ? 'has-error' :'' }}">
                                    <label>Account</label>
                                    <select class="form-control" id="modal-account" name="account">
                                        <option value="">Select Account</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Cheque No.</label>
                                    <input class="form-control" type="text" name="cheque_no" placeholder="Enter Cheque No." value="{{ old('cheque_no') }}">
                                </div>

                                <div class="form-group {{ $errors->has('cheque_image') ? 'has-error' :'' }}">
                                    <label>Cheque Image</label>
                                    <input class="form-control" name="cheque_image" type="file">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th colspan="4" class="text-right">Product Sub Total</th>
                                    <th id="product-sub-total">৳0.00</th>
                                </tr>


                                <tr>
                                    <th colspan="4" class="text-right">Product Discount Type</th>
                                    <td>
                                        <div class="form-group">
                                            <select class="form-control" id="modal-discount-type" name="discount_type">
                                                <option value="0" {{ old('discount_type') == '0' ? 'selected' : '' }}>Select Discount Type</option>
                                                <option value="1" {{ old('discount_type') == '1' ? 'selected' : '' }}>Percentage Discount</option>
                                                <option value="2" {{ old('discount_type') == '2' ? 'selected' : '' }}>Flat Discount</option>
                                            </select>
                                        </div>
                                    </td>
                                </tr>
                                <tr id="modal-discount-percentage" >
                                    <th colspan="4" class="text-right">Product Discount Percentage (%)</th>
                                    <td>
                                        <div class="form-group {{ $errors->has('discount') ? 'has-error' :'' }}">
                                            <input type="text" class="form-control" name="discount" id="discount" value="{{ empty(old('discount')) ? ($errors->has('discount') ? '' : '0') : old('discount') }}">
                                            <span id="discount-amount">৳0.00</span>
                                        </div>
                                    </td>
                                </tr>

                                <tr id="modal-discount-flat" >
                                    <th colspan="4" class="text-right">Product Discount In Amount</th>
                                    <td>
                                        <div class="form-group {{ $errors->has('discount_in_amount') ? 'has-error' :'' }}">
                                            <input type="text" class="form-control" name="discount_in_amount" id="discount_in_amount" value="{{ empty(old('discount_in_amount')) ? ($errors->has('discount_in_amount') ? '' : '0') : old('discount_in_amount') }}">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th colspan="4" class="text-right">Total</th>
                                    <th id="final-amount">৳0.00</th>
                                </tr>
                                <tr>
                                    <th colspan="4" class="text-right">Paid</th>
                                    <td>
                                        <div class="form-group {{ $errors->has('paid') ? 'has-error' :'' }}">
                                            <input type="text" class="form-control" name="paid" id="paid" value="{{ empty(old('paid')) ? ($errors->has('paid') ? '' : '0') : old('paid') }}">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th colspan="4" class="text-right">Due</th>
                                    <th id="due">৳0.00</th>
                                </tr>

                            </table>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->

                <div class="box-footer">
                    <input type="hidden" name="total" id="total">
                    <input type="hidden" name="due_total" id="due_total">
                    <button type="submit" class="btn btn-primary btn-save ">Save</button>
                </div>
            </div>
        </div>
    </div>
 </form>
    <template id="template-product">
        <tr class="product-item">
            <td>
                <div class="form-group">
                    <select class="form-control product_type select2" style="width: 100%;" name="product_type[]" required>
                        <option value="">Select type</option>
                            @foreach($productTypes as $productType)
                                <option value="{{ $productType->id }}">{{ $productType->name }}</option>
                            @endforeach
                    </select>
                </div>
            </td>
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
                    <select class="form-control product_color select2" style="width: 100%;" name="product_color[]">
                        <option value="">Select Color</option>
                        @foreach($colors as $color)
                            <option value="{{ $color->id }}">{{ $color->name }}</option>
                        @endforeach
                    </select>
                </div>
            </td>
            <td>
                <div class="form-group">
                    <textarea  style="display: none" class="form-control imei_code" name="imei_code[]" rows="2"></textarea>
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input type="text" step="any" class="form-control quantity" name="quantity[]">
                </div>
            </td>
{{--            <td  class="quantity">0.00</td>--}}
            <td>
                <div class="form-group">
                    <input type="text" class="form-control unit_price" name="unit_price[]">
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input type="text" class="form-control selling_price" name="selling_price[]">
                </div>
            </td>
            <td  class="total-cost">৳0.00</td>
            <td  class="text-center">
                <a role="button" class="btn btn-danger btn-sm btn-remove">X</a>
            </td>
        </tr>
    </template>
@endsection

@section('script')

    <script>
        $(function () {
            calculate();
            $('body').on('change','.product_type', function () {
                var productTypeId = $(this).val();
                var itemProductType = $(this);
                itemProductType.closest('tr').find('.product_brand').html('<option value="">Select Brand</option>');
                itemProductType.closest('tr').find('.product_model').html('<option value="">Select Product Model</option>');

                var selected = itemProductType.closest('tr').find('.product_brand').attr("data-selected-brand");

                if (productTypeId != '') {

                    $.ajax({
                        method: "GET",
                        url: "{{ route('get_product_type') }}",
                        data: { productTypeId: productTypeId }
                    }).done(function( data ) {
                        if(data.imei == 1){
                            itemProductType.closest('tr').find('.imei_code').show();
                        }else{
                            itemProductType.closest('tr').find('.imei_code').hide();
                        }

                    });



                    $.ajax({
                        method: "GET",
                        url: "{{ route('get_brands') }}",
                        data: { productTypeId: productTypeId }
                    }).done(function( data ) {
                        $.each(data, function( index, item ) {
                            if (selected == item.id)
                                itemProductType.closest('tr').find('.product_brand').append('<option value="'+item.id+'" selected>'+item.name+'</option>');
                            else
                                itemProductType.closest('tr').find('.product_brand').append('<option value="'+item.id+'">'+item.name+'</option>');
                        });
                        itemProductType.closest('tr').find('.product_brand').trigger('change');
                    });
                }

            });


            $('.product_type').trigger('change');

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
                        $('.product_model').trigger('change');

                    });
                }

            });
            $('.product_brand').trigger('change');



            //Product Model
            $('body').on('change','.product_model', function () {
                var productModelId = $(this).val();
                var itemProduct = $(this);
                itemProduct.closest('tr').find('.unit_price').val('');
                itemProduct.closest('tr').find('.selling_price').val('');
                if (productModelId != '') {
                    $.ajax({
                        method: "GET",
                        url: "{{ route('get_product_model_price') }}",
                        data: { productModelId: productModelId }
                    }).done(function(response) {
                        itemProduct.closest('tr').find('.unit_price').val(response.inventory.purchase_price);
                        itemProduct.closest('tr').find('.selling_price').val(response.inventory.selling_price);
                        calculate();
                    });

                }
            });

            $('.product_model').trigger('change');



            $('#btn-add-product').click(function () {
                var html = $('#template-product').html();
                var item = $(html);
                $('#product-container').append(item);

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

            $('body').on('keyup', '.quantity, .unit_price, #discount, #discount_in_amount, #paid', function () {
                calculate();
            });



            if ($('.product-item').length <= 1 ) {
                $('.btn-remove').hide();
            } else {
                $('.btn-remove').show();
            }
            initProduct();
            calculate();

            $('#modal-discount-type').change(function () {
                if ($(this).val() == '1') {
                    $('#modal-discount-percentage').show();
                    $('#modal-discount-flat').hide();
                } else if ($(this).val() == '2'){
                    $('#modal-discount-flat').show();
                    $('#modal-discount-percentage').hide();
                }else{
                    $('#modal-discount-flat').hide();
                    $('#modal-discount-percentage').hide();
                }
            });

            $('#modal-discount-type').trigger('change');

            $('#modal-pay-type').change(function () {
                if ($(this).val() == '1' || $(this).val() == '3') {
                    $('#modal-bank-info').hide();
                } else {
                    $('#modal-bank-info').show();
                }
            });

            $('#modal-pay-type').trigger('change');

            var selectedBranch = '{{ old('branch') }}';
            var selectedAccount = '{{ old('account') }}';

            $('#modal-bank').change(function () {
                var bankId = $(this).val();
                $('#modal-branch').html('<option value="">Select Branch</option>');
                $('#modal-account').html('<option value="">Select Account</option>');

                if (bankId != '') {
                    $.ajax({
                        method: "GET",
                        url: "{{ route('get_branch') }}",
                        data: { bankId: bankId }
                    }).done(function( response ) {
                        $.each(response, function( index, item ) {
                            if (selectedBranch == item.id)
                                $('#modal-branch').append('<option value="'+item.id+'" selected>'+item.name+'</option>');
                            else
                                $('#modal-branch').append('<option value="'+item.id+'">'+item.name+'</option>');
                        });

                        $('#modal-branch').trigger('change');
                    });
                }

                $('#modal-branch').trigger('change');
            });

            $('#modal-branch').change(function () {
                var branchId = $(this).val();
                $('#modal-account').html('<option value="">Select Account</option>');

                if (branchId != '') {
                    $.ajax({
                        method: "GET",
                        url: "{{ route('get_bank_account') }}",
                        data: { branchId: branchId }
                    }).done(function( response ) {
                        $.each(response, function( index, item ) {
                            if (selectedAccount == item.id)
                                $('#modal-account').append('<option value="'+item.id+'" selected>'+item.account_no+'</option>');
                            else
                                $('#modal-account').append('<option value="'+item.id+'">'+item.account_no+'</option>');
                        });
                    });
                }
            });

            $('#modal-bank').trigger('change');

            //New Line in Imei
            $(document).ready(function() {

                $('body').on('keydown','.imei_code', function (e) {
                    if(e.keyCode == 13){
                        e.preventDefault();
                        var curr = getNewLines(this);
                        var val = $(this).val();
                        var end = val.length;
                        $(this).val( val.substr(0, curr) + '\n' + val.substr(curr, end));

                        $(this).closest('tr').find('.quantity').val( function(i, count) {
                            return ++count;
                        });
                        calculate();

                    }
                })
            });


            function getNewLines(el) {
                if (el.selectionStart) {
                    return el.selectionStart;
                }
                else if (document.selection) {
                    el.focus();

                    var r = document.selection.createRange();
                    if (r == null) {
                        return 0;
                    }

                    var re = el.createTextRange(),
                        rc = re.duplicate();
                    re.moveToBookmark(r.getBookmark());
                    rc.setEndPoint('EndToStart', re);

                    return rc.text.length;
                }
                return 0;
            }
        });


        function calculate() {
            var productSubTotal = 0;

            var discount = $('#discount').val();
            var discount_in_amount = $('#discount_in_amount').val();
            var paid = $('#paid').val();

            if (discount == '' || discount < 0 || !$.isNumeric(discount))
                discount = 0;

            if (discount_in_amount == '' || discount_in_amount < 0 || !$.isNumeric(discount_in_amount))
                discount_in_amount = 0;

            if (paid == '' || paid < 0 || !$.isNumeric(paid))
                paid = 0;


            $('.product-item').each(function(i, obj) {
                var quantity = $('.quantity:eq('+i+')').val();
                var unit_price = $('.unit_price:eq('+i+')').val();

                if (quantity == '' || quantity < 0 || !$.isNumeric(quantity))
                    quantity = 0;

                if (unit_price == '' || unit_price < 0 || !$.isNumeric(unit_price))
                    unit_price = 0;
                $('.total-cost:eq('+i+')').html('৳' + (quantity * unit_price).toFixed(2) );
                productSubTotal += quantity * unit_price;
            });

            var productTotalDiscount = (productSubTotal * discount) / 100;

            $('#product-sub-total').html('৳' + productSubTotal.toFixed(2));
            $('#discount-amount').html('৳' + productTotalDiscount.toFixed(2));

            var total = parseFloat(productSubTotal) -
                parseFloat(productTotalDiscount) - discount_in_amount;



            var due = parseFloat(total) - parseFloat(paid);

            $('#final-amount').html('৳' + total.toFixed(2));
            $('#due').html('৳' + due.toFixed(2));
            $('#total').val(total.toFixed(2));
            $('#due_total').val(due.toFixed(2));



        }

        function initProduct() {
            $('.select2').select2();
        }



    </script>
@endsection
