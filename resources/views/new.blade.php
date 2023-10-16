@extends('layouts.app')

@section('style')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/select2/dist/css/select2.min.css') }}">
    <!-- jQuery UI -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.css" />
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
@endsection

@section('title')
    LPG Product Received
@endsection

@section('content')
    <form method="POST" enctype="multipart/form-data" action="{{ route('sales_order.create') }}">
        @csrf
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Order Information</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group {{ $errors->has('company') ? 'has-error' :'' }}" >
                                    <label>Company *</label>

                                    <select class="form-control company select2" style="width: 100%;" id="company" name="company" data-placeholder="Select Company">
                                        <option value="">Select Company</option>
                                        @foreach($companies as $company)
                                            <option value="{{$company->id}}" {{old('company')==$company->id ? "selected" : ''}}>{{$company->name}}</option>
                                        @endforeach

                                    </select>
                                    @error('company')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group {{ $errors->has('customer') ? 'has-error' :'' }}" id="form-group-customer">
                                    <label>Delivery Man</label>

                                    <select class="form-control select2" style="width: 100%;" id="customer" name="customer" data-placeholder="Select Delivery Man">
                                        <option value="">Select Delivery Man *</option>
                                        @foreach($customers as $customer)
                                            <option value="{{$customer->id}}" {{old('customer')==$customer->id?"selected":''}}>{{$customer->name}}</option>
                                        @endforeach

                                    </select>
                                    @error('customer')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group {{ $errors->has('hr') ? 'has-error' :'' }}" id="form-group-customer">
                                    <label>Hr</label>

                                    <select class="form-control select2" style="width: 100%;" id="hr" name="hr" data-placeholder="Select Hr">
                                        <option value="">Select Hr</option>
                                        @foreach($hrs as $hr)
                                            <option value="{{$hr->id}}" {{old('hr')==$hr->id?"selected":''}}>{{$hr->name}}</option>
                                        @endforeach

                                    </select>
                                    @error('hr')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group {{ $errors->has('date') ? 'has-error' :'' }}">
                                    <label>Date *</label>

                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control pull-right" id="date" name="date" value="{{ empty(old('date')) ? ($errors->has('date') ? '' : date('Y-m-d')) : old('date') }}" autocomplete="off" required>
                                    </div>
                                    <!-- /.input group -->

                                    @error('date')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group {{ $errors->has('market_name') ? 'has-error' :'' }}">
                                    <label>Market Name *</label>

                                    <input class="form-control" type="text" name="market_name" value="{{ old('market_name') }}">

                                    @error('market_name')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Products</h3>
                    </div>
                    <!-- /.box-header -->

                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>Category *</th>
                                    <th>Sub Category *</th>
                                    <th>Sub Sub Category *</th>
                                    <th>Product *</th>
                                    <th>Quantity</th>
                                    <th>Unit Price</th>
                                    <th>Total Cost</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody id="product-container">
                                @if (old('product') != null && sizeof(old('product')) > 0)
                                    @foreach (old('product') as $item)
                                        <tr class="product-item">

                                            <td>
                                                <div
                                                    class="form-group {{ $errors->has('category.' . $loop->index) ? 'has-error' : '' }}">
                                                    <select class="form-control category" style="width: 100%;"
                                                            name="category[]"
                                                            data-selected-category="{{ old('category.' . $loop->index) }}"
                                                            required>
                                                        <option value="">Select Category</option>
                                                        @foreach ($categories as $category)
                                                            <option
                                                                {{ old('category.' . $loop->index) == $category->id ? 'selected' : '' }}
                                                                value="{{ $category->id }}">
                                                                {{ $category->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </td>
                                            <td>
                                                <div
                                                    class="form-group {{ $errors->has('sub_category.' . $loop->index) ? 'has-error' : '' }}">
                                                    <select class="form-control sub_category" style="width: 100%;"
                                                            name="sub_category[]"
                                                            data-selected-subCategory="{{ old('sub_category.' . $loop->index) }}"
                                                            required>
                                                        <option value="">Select Sub Category</option>
                                                    </select>
                                                </div>
                                            </td>
                                            <td>
                                                <div
                                                    class="form-group {{ $errors->has('sub_sub_category.' . $loop->index) ? 'has-error' : '' }}">
                                                    <select class="form-control sub_sub_category" style="width: 100%;"
                                                            name="sub_sub_category[]"
                                                            data-selected-subSubCategory="{{ old('sub_sub_category.' . $loop->index) }}"
                                                            required>
                                                        <option value="">Select Sub Sub Category</option>
                                                    </select>
                                                </div>
                                            </td>
                                            <td>
                                                <div
                                                    class="form-group {{ $errors->has('product.' . $loop->index) ? 'has-error' : '' }}">
                                                    <select class="form-control product" style="width: 100%;"
                                                            name="product[]"
                                                            data-selected-product="{{ old('product.' . $loop->index) }}"
                                                            required>
                                                        <option value="">Select Product</option>
                                                    </select>
                                                </div>
                                            </td>

                                            <td>
                                                <div
                                                    class="form-group {{ $errors->has('quantity.' . $loop->index) ? 'has-error' : '' }}">
                                                    <input type="number" step="any" class="form-control quantity"
                                                           name="quantity[]"
                                                           value="{{ old('quantity.' . $loop->index) }}">
                                                </div>
                                            </td>
                                            <td>
                                                <div
                                                    class="form-group {{ $errors->has('unit_price.' . $loop->index) ? 'has-error' : '' }}">
                                                    <input type="text" class="form-control unit_price"
                                                           name="unit_price[]"
                                                           value="{{ old('unit_price.' . $loop->index) }}">
                                                </div>
                                            </td>

                                            <td class="total-cost">৳0.00</td>
                                            <td class="text-center">
                                                <a role="button" class="btn btn-danger btn-sm btn-remove">X</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr class="product-item">

                                        <td>
                                            <div class="form-group">
                                                <select class="form-control category" style="width: 200px;"
                                                        name="category[]" required>
                                                    <option value="">Select Category</option>
                                                    @foreach ($categories as $category)
                                                        <option value="{{ $category->id }}">{{ $category->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <select class="form-control sub_category" style="width: 100%;"
                                                        name="sub_category[]" required>
                                                    <option value="">Select Sub Category</option>
                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <select class="form-control sub_sub_category" style="width: 100%;"
                                                        name="sub_sub_category[]" required>
                                                    <option value="">Select Sub Sub Category</option>
                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <select class="form-control product" style="width: 100%;"
                                                        name="product[]" required>
                                                    <option value="">Select Product</option>
                                                </select>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="number" step="any" class="form-control quantity"
                                                       name="quantity[]">
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input type="text" class="form-control unit_price" name="unit_price[]">
                                            </div>
                                        </td>

                                        <td class="total-cost">৳0.00</td>
                                        <td class="text-center">
                                            <a role="button" class="btn btn-danger btn-sm btn-remove">X</a>
                                        </td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>

                        <a role="button" class="btn btn-info btn-sm" id="btn-add-product" style="margin-bottom: 10px">Add Product</a>
                    </div>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-md-12">
                <div class="box">

                    <div class="box-footer">
                        <input type="hidden" name="total" id="total">
                        <input type="hidden" name="due_total" id="due_total">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <template id="template-product">
        <tr class="product-item">
            <td>
                <div class="form-group">
                    <select class="form-control product select2" style="width: 100%;" name="product[]" required>
                        <option value="">Select Product</option>
                    </select>
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input type="number" step="any" class="form-control carton_size" name="carton_size[]" readonly>
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input type="number" step="any" class="form-control carton_number" name="carton_number[]">
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input type="number" step="any" class="form-control carton_pcs" name="carton_pcs[]">
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="number" step="any" class="form-control quantity" name="quantity[]" readonly>
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="text" class="form-control unit_price" name="unit_price[]">
                </div>
            </td>


            <td class="total-cost">৳0.00</td>
            <td class="text-center">
                <a role="button" class="btn btn-danger btn-sm btn-remove">X</a>
            </td>
        </tr>

        <tr>
            <td colspan="8" class="available-quantity" style="font-weight: bold"></td>
        </tr>
    </template>


@endsection

@section('script')
    <!-- Select2 -->
    <script src="{{ asset('themes/backend/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
    <!-- sweet alert 2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <!-- jQuery UI -->
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <!-- bootstrap datepicker -->
    <script src="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>

    <script>
        $(function () {
            //Initialize Select2 Elements
            $('.select2').select2()

            //Date picker
            $('#date, #next_payment').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd'
            });

            var message = '{{ session('message') }}';

            if (!window.performance || window.performance.navigation.type != window.performance.navigation.TYPE_BACK_FORWARD) {
                if (message != '') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: message,
                    });
                }
            }


            $('#btn-add-product').click(function () {
                var html = $('#template-product').html();
                var item = $(html);
                $('#product-container').append(item);

                initProduct();
                var supplierID = $("#company").val();

                if (supplierID != '') {
                    $.ajax({
                        method: "GET",
                        url: "{{ route('get_products_form_inventory') }}",
                        data: {supplierID: supplierID}
                    }).done(function (response) {
                        $.each(response, function( index, item ) {
                            $(".product").last().append('<option value="'+item.product.id+'">'+item.product.name+' - '+item.quantity+'</option>');
                        });

                    });
                }
            });



            $('body').on('change', '.company', function () {
                var supplierID = $(this).val();
                $('.product').html('<option value="">Select Product</option>');

                //const numItems = $('.product-item').length

                // var selected = $("tr.product-item:nth-child(2)").closest('td').find('.product').attr("data-selected-product");

                var selected = $('.product').attr("data-selected-product");

                //var selected = $('.product').val($('select[name="product"] option:selected').attr("data-selected-product"));

                if (supplierID != '') {
                    $.ajax({
                        method: "GET",
                        url: "{{ route('get_products_form_inventory') }}",
                        data: {supplierID: supplierID}
                    }).done(function (response) {
                        //console.log(response);
                        $.each(response, function( index, item ) {
                            if (selected == item.id)
                                $('.product').append('<option value="'+item.product.id+'" selected>'+item.product.name+' - '+item.quantity+'</option>');
                            else
                                $('.product').append('<option value="'+item.product.id+'" >'+item.product.name+' - '+item.quantity+'</option>');
                        });


                    });
                }
            });

            $('.company').trigger('change');



            $('body').on('change', '.product', function (e) {
                var productId = $(this).closest('.product-item').find('.product').val();
                $this = $(this);
                var index = $('.' + e.target.name.slice(0, -2)).index(this);

                if (productId != '' ) {
                    $.ajax({
                        method: "GET",
                        url: "{{ route('sale_product.details') }}",
                        data: { productId: productId }
                    }).done(function( response ) {
                        if (response.success) {
                            $this.closest('tr').find('.quantity').val(1);
                            $this.closest('tr').find('.quantity').attr({
                                "max" : response.count,
                                "min" : 1
                            });
                            // $this.closest('tr').find('.carton_size').val(response.data.carton_size);
                            // $this.closest('tr').find('.carton_number').val(response.data.carton_number);
                            // $this.closest('tr').find('.carton_pcs').val(response.data.carton_pcs);
                            $this.closest('tr').find('.unit_price').val(response.data.last_selling_price);
                            $this.closest('tr').find('.carton_size').val(response.data.carton_size);
                            $('.available-quantity:eq('+index+')').html('Available: ' + response.count);
                            calculate();
                        } else {
                            $this.closest('tr').find('.quantity').val('');
                            $this.closest('tr').find('.unit_price').val('');
                            $('.available-quantity:eq('+index+')').html('');
                            calculate();
                        }
                    });
                }
            });

            $('.product').trigger('change');

            $('body').on('click', '.btn-remove', function () {
                var index = $('.btn-remove').index(this);
                $(this).closest('.product-item').remove();

                $('.available-quantity:eq('+index+')').closest('tr').remove();
                calculate();
            });
            $('body').on('keyup', '.carton_size,.carton_number,.carton_pcs,.quantity, .unit_price, .service_quantity, .service_unit_price,#commission, #vat, #service_vat, #discount, #service_discount, #paid', function () {
                calculate();
            });

            // $('body').on('keyup', '.quantity, .unit_price, .service_quantity, .service_unit_price, #vat, #service_vat, #discount, #service_discount, #paid', function () {
            //     calculate();
            // });

            $('body').on('change', '.quantity, .unit_price, .service_quantity, .service_unit_price', function () {
                calculate();
            });

            calculate();

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

            // Service
            $('#btn-add-service').click(function () {
                var html = $('#template-service').html();
                var item = $(html);

                $('#service-container').append(item);

                if ($('.product-item').length + $('.service-item').length >= 1 ) {
                    $('.btn-remove').show();
                    $('.btn-remove-service').show();
                }
            });

            $('body').on('click', '.btn-remove-service', function () {
                $(this).closest('.service-item').remove();
                calculate();

                if ($('.product-item').length + $('.service-item').length <= 1 ) {
                    $('.btn-remove').hide();
                    $('.btn-remove-service').hide();
                }
            });
        });

        function calculate() {
            var productSubTotal = 0;
            var serviceSubTotal = 0;

            var vat = $('#vat').val();
            var discount = $('#discount').val();
            var serviceVat = $('#service_vat').val();
            var serviceDiscount = $('#service_discount').val();
            var paid = $('#paid').val();
            var commission = $('#commission').val();


            if (vat == '' || vat < 0 || !$.isNumeric(vat))
                vat = 0;

            if (discount == '' || discount < 0 || !$.isNumeric(discount))
                discount = 0;

            if (paid == '' || paid < 0 || !$.isNumeric(paid))
                paid = 0;

            if (serviceVat == '' || serviceVat < 0 || !$.isNumeric(serviceVat))
                serviceVat = 0;

            if (serviceDiscount == '' || serviceDiscount < 0 || !$.isNumeric(serviceDiscount))
                serviceDiscount = 0;

            if (commission == '' || commission < 0 || !$.isNumeric(commission))
                commission = 0;

            $('.product-item').each(function(i, obj) {
                var carton_size = $('.carton_size:eq('+i+')').val();
                var carton_number = $('.carton_number:eq('+i+')').val();
                var carton_pcs = $('.carton_pcs:eq('+i+')').val();
                var quantity = $('.quantity:eq('+i+')').val();
                var unit_price = $('.unit_price:eq('+i+')').val();

                if (carton_size == '' || carton_size < 0 || !$.isNumeric(carton_size))
                    carton_size = 0;
                if (carton_number == '' || carton_number < 0 || !$.isNumeric(carton_number))
                    carton_number = 0;
                if (carton_pcs == '' || carton_pcs < 0 || !$.isNumeric(carton_pcs))
                    carton_pcs = 0;

                if (quantity == '' || quantity < 0 || !$.isNumeric(quantity))
                    quantity = 0;

                if (unit_price == '' || unit_price < 0 || !$.isNumeric(unit_price))
                    unit_price = 0;

                quantity = (carton_size * carton_number) +  parseFloat(carton_pcs);

                $('.quantity:eq('+i+')').val( quantity );

                $('.total-cost:eq('+i+')').html('৳' + (quantity * unit_price).toFixed(2) );

                productSubTotal += quantity * unit_price;
            });

            $('.service-item').each(function(i, obj) {
                var quantity = $('.service_quantity:eq('+i+')').val();
                var unit_price = $('.service_unit_price:eq('+i+')').val();


                if (quantity == '' || quantity < 0 || !$.isNumeric(quantity))
                    quantity = 0;

                if (unit_price == '' || unit_price < 0 || !$.isNumeric(unit_price))
                    unit_price = 0;

                $('.service-total-cost:eq('+i+')').html('৳' + (quantity * unit_price).toFixed(2) );
                serviceSubTotal += quantity * unit_price;
            });


            var productTotalVat = (productSubTotal * vat) / 100;
            var total_commission = (productSubTotal * commission) / 100;
            var serviceTotalVat = (serviceSubTotal * serviceVat) / 100;


            $('#product-sub-total').html('৳' + productSubTotal.toFixed(2));
            $('#service-sub-total').html('৳' + serviceSubTotal.toFixed(2));

            $('#vat_total').html('৳' + productTotalVat.toFixed(2));
            $('#commission_total').html('৳' + total_commission.toFixed(2));
            $('#service_vat_total').html('৳' + serviceTotalVat.toFixed(2));

            var total = parseFloat(productSubTotal) + parseFloat(serviceSubTotal) +
                parseFloat(productTotalVat) + parseFloat(serviceTotalVat) -
                parseFloat(discount) - parseFloat(serviceDiscount);

            var due = parseFloat(total) - parseFloat(paid);
            $('#final-amount').html('৳' + total.toFixed(2));
            $('#due').html('৳' + due.toFixed(2));
            $('#total').val(total.toFixed(2));
            $('#due_total').val(due.toFixed(2));

        }

        function initProduct() {
            $('.product').select2();
        }
    </script>
@endsection
