@extends('layouts.app')

@section('title')
    Sales Order
@endsection
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
@section('content')
<audio id="add_more_sound" style="display: none">
    <source src="{{ asset('img/add.mp3') }}" type="audio/mpeg">
    Your browser does not support the audio element.
</audio>
<audio id="remove_sound" style="display: none">
    <source src="{{ asset('img/remove.mp3') }}" type="audio/mpeg">
    Your browser does not support the audio element.
</audio>


<div class="modal fade" id="modal-scan"  data-backdrop="static" data-keyboard="false" style="padding: 0">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close dce-btn-close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Scan Information</h4>
            </div>
            <div class="modal-body" style="padding: 0">
                <input style="display: none" type="text" id="result" title="Double click to clear!" readonly="true" class="latest-result" placeholder="The Last Read Barcode">

                <div id="UIElement" class="UIElement">
                    <span   id='lib-load' style='font-size:x-large;display: none' hidden>Loading Library...</span>
                    <button style="display: none" id="showScanner" hidden>Show The Scanner</button>
                </div>
                <div style="display: none" id="results"></div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>


<form  method="POST" enctype="multipart/form-data" action="{{ route('sr_sales_order.create') }}">
@csrf

    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Retailer Information</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group {{ $errors->has('retailer') ? 'has-error' :'' }}">
                                <label>Retailer </label>
                                <select name="retailer" class="form-control select2" id="retailer">
                                    <option value="">Select Retailer</option>
                                    @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }}-{{ $customer->mobile_no }}</option>
                                    @endforeach
                                </select>
                                @error('retailer')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
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
                    <div class="row">
                        <div class="col-xs-9 col-md-4">
                            <div class="form-group">
                                <input autocomplete="off" type="text" id="imei_no" class="form-control" placeholder="Enter IMEI No">
                            </div>
                        </div>
                        <div class="col-xs-2 col-md-2">
                           <button style="margin-top: 4px" class="btn btn-primary btn-sm" type="button" id="barcode-scan-custom"><i class="fa fa-barcode"></i> Scan</button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>IMEI/Barcode No</th>
                                <th>Product Brand</th>
                                <th>Product Model</th>
                                <th>Color</th>
                                <th width="10%">Quantity</th>
                                <th width="10%">Unit Price</th>
                                <th>Total Cost</th>
                                <th></th>
                            </tr>
                            </thead>

                            <tbody id="product-container">
                            @if (old('imei') != null && sizeof(old('imei')) > 0)
                                @foreach(old('imei') as $item)
                                    <tr class="product-item">

                                        <td>
                                            <div class="form-group {{ $errors->has('imei.'.$loop->index) ? 'has-error' :'' }}">
                                                <input type="text" class="form-control imei" name="imei[]" value="{{ old('imei.'.$loop->index) }}" readonly>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group {{ $errors->has('product_brand.'.$loop->index) ? 'has-error' :'' }}">
                                                <input type="text" class="form-control product_brand" name="product_brand[]" value="{{ old('product_brand.'.$loop->index) }}" readonly>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group {{ $errors->has('product_model.'.$loop->index) ? 'has-error' :'' }}">
                                                <input type="text" class="form-control product_model" name="product_model[]" value="{{ old('product_model.'.$loop->index) }}" readonly>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group {{ $errors->has('color.'.$loop->index) ? 'has-error' :'' }}">
                                                <input type="text" class="form-control color" name="color[]" value="{{ old('color.'.$loop->index) }}" readonly>
                                            </div>
                                        </td>


                                        <td>
                                            <div class="form-group {{ $errors->has('quantity.'.$loop->index) ? 'has-error' :'' }}">
                                                <input type="hidden"  class="product_type" name="product_type[]" value="{{ old('product_type.'.$loop->index) }}">
                                                <input {{ old('product_type.'.$loop->index) == 1 ? 'readonly' : '' }} type="number" step="any" class="form-control quantity" name="quantity[]" value="{{ old('quantity.'.$loop->index) }}">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group {{ $errors->has('unit_price.'.$loop->index) ? 'has-error' :'' }}">
                                                <input type="text" class="form-control unit_price" name="unit_price[]" value="{{ old('unit_price.'.$loop->index) }}">
                                            </div>
                                        </td>

                                        <td class="total-cost">৳0.00</td>
                                        <td class="text-center">
                                            <a role="button" class="btn btn-danger btn-sm btn-remove">X</a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
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


{{--                             <tr>--}}
{{--                                <th colspan="4" class="text-right">Product VAT (%)</th>--}}
{{--                                <td>--}}
{{--                                    <div class="form-group {{ $errors->has('vat') ? 'has-error' :'' }}">--}}
{{--                                        <input type="text" class="form-control" name="vat" id="vat" value="{{ empty(old('vat')) ? ($errors->has('vat') ? '' : '0') : old('vat') }}">--}}
{{--                                        <span id="vat_total">৳0.00</span>--}}
{{--                                    </div>--}}
{{--                                </td>--}}
{{--                            </tr>--}}

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
                    <input type="text" class="form-control imei" name="imei[]" readonly>
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input type="text" class="form-control product_brand" name="product_brand[]" readonly>
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input type="text" class="form-control product_model" name="product_model[]" readonly>
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input type="text" class="form-control color" name="color[]" readonly>
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="hidden"  class="product_type" name="product_type[]">
                    <input type="number" readonly step="any" class="form-control quantity" name="quantity[]">
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
    </template>

@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/dynamsoft-javascript-barcode@9.3.1/dist/dbr.js"></script>

    <script>
        /** LICENSE ALERT - README
         * To use the library, you need to first specify a license key using the API "license" as shown below.
         */

      Dynamsoft.DBR.BarcodeReader.license = "t0183SwUAAHV6Tr1KyV2RMel8hFX871ZcbYSOrYx3YAdMjFxFoQzBlASTbEQDeVsGX3FMlr2xOcaneglKPWBGdVHWueiJwexfMp2nU0vLY7Q6RHuhIVrMQ7T8b7VE13CcY/9lfRresAduAdA0IAlACcRyKuCu223LkAHcA7QDxKSbwMci9/Gu511q9G281HTyAKce7ywn9hhnOa6/Ot2suibKSykBKLucAdwDtAPEADLAslpjhd5YehHM";


        /**
         * You can visit https://www.dynamsoft.com/customer/license/trialLicense?utm_source=zip&product=dbr&package=js to get your own trial license good for 30 days.
         * Note that if you downloaded this sample from Dynamsoft while logged in, the above license key may already be your own 30-day trial license.
         * For more information, see https://www.dynamsoft.com/barcode-reader/programming/javascript/user-guide/?ver=9.3.1&utm_source=zip#specify-the-license or contact support@dynamsoft.com.
         * LICENSE ALERT - THE END
         */

            // scanner for decoding video
        let pScanner = null;
        async function startWebCameraCustom() {
            //Load the library on page load to speed things up.
            try {
                document.getElementById('lib-load').hidden = false;
                await Dynamsoft.DBR.BarcodeReader.loadWasm();
                startBarcodeScanner();
            } catch (ex) {
                let errMsg;
                if (ex.message.includes("network connection error")) {
                    errMsg = "Failed to connect to Dynamsoft License Server: network connection error. Check your Internet connection or contact Dynamsoft Support (support@dynamsoft.com) to acquire an offline license.";
                } else {
                    errMsg = ex.message||ex;
                }
                console.error(errMsg);
                alert(errMsg);
            }
            document.getElementById('result').addEventListener('dblclick', async() => {
                document.getElementById('result').value = "";
            });
            document.getElementById('showScanner').addEventListener('click', async() => {
                if (pScanner)(await pScanner).show();
            });
        };

        // decode video from camera
        async function startBarcodeScanner() {
            try {
                let scanner = await (pScanner = pScanner || Dynamsoft.DBR.BarcodeScanner.createInstance());
                document.getElementById('showScanner').hidden = true;
                scanner.onFrameRead = (_results) => {
                    for (let result of _results) {
                        let newElements = [];
                        const format = result.barcodeFormat ? result.barcodeFormatString : result.barcodeFormatString_2;
                        newElements.push(createASpan(format + ": "));
                        newElements.push(createASpan(result.barcodeText, "resultText"));

                        $("#imei_no").val(result.barcodeText);
                        if(result.barcodeText != ''){
                            $(".dce-btn-close").click();
                            $("#modal-scan").modal('hide');
                            productAppend(result.barcodeText);
                        }

                        newElements.push(document.createElement('br'));
                        if (result.barcodeText.indexOf("Attention(exceptionCode") != -1) {
                            newElements.push(createASpan(" Error: " + result.exception.message));
                            newElements.push(document.createElement('br'));
                        }
                        for (let span of newElements) {

                            document.getElementById('results').appendChild(span);
                        }
                        document.getElementById('results').scrollTop = document.getElementById('results').scrollHeight;
                    }
                };
                scanner.onUniqueRead = (txt, result) => {
                    const format = result.barcodeFormat ? result.barcodeFormatString : result.barcodeFormatString_2;
                    document.getElementById('result').value = format + ": " + txt;
                    document.getElementById('result').focus();
                    setTimeout(() => {
                        document.getElementById('result').blur();
                    }, 2000);
                };
                document.getElementById('UIElement').appendChild(scanner.getUIElement());
                await scanner.show();
                document.getElementById('lib-load').hidden = true;
                document.getElementById('results').style.visibility = "visible";
            } catch (ex) {
                let errMsg;
                if (ex.message.includes("network connection error")) {
                    errMsg = "Failed to connect to Dynamsoft License Server: network connection error. Check your Internet connection or contact Dynamsoft Support (support@dynamsoft.com) to acquire an offline license.";
                } else {
                    errMsg = ex.message||ex;
                }
                console.error(errMsg);
                alert(errMsg);
            }
        }

        function createASpan(txt, className) {
            let newSPAN = document.createElement("span");
            newSPAN.textContent = txt;
            if (className)
                newSPAN.className = className;
            return newSPAN;
        }
    </script>
    <script>
        var ImeiSerials = [];



        $(function () {
            $("#barcode-scan-custom").click(function(){
                $("#modal-scan").modal('show');
                startWebCameraCustom();
            })

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

            $( ".imei" ).each(function( index ) {
                if ($(this).val() != '') {
                    ImeiSerials.push($(this).val());
                }
            });


            $('body').on('click', '.btn-remove', function () {
                var imei = $(this).closest('tr').find('.imei').val();
                $(this).closest('.product-item').remove();
                var removeItem = document.getElementById("remove_sound");
                removeItem.play();
                calculate();

                if ($('.product-item').length<= 1 ) {
                    $('.btn-remove').hide();
                }
                ImeiSerials = $.grep(ImeiSerials, function(value) {
                    return value != imei;
                });
            });

            $('body').on('keyup', '.quantity, .unit_price,  #vat, #discount, #discount_in_amount, #paid', function () {
                calculate();
            });

            $('body').on('change', '.quantity, .unit_price', function () {
                calculate();
            });

            if ($('.product-item').length.length <= 1 ) {
                $('.btn-remove').hide();
            } else {
                $('.btn-remove').show();
            }
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

            $('body').on('keypress', '#imei_no', function (e) {
                if (e.keyCode == 13) {
                    var imeiNo = $(this).val();

                    if (imeiNo != '') {
                        productAppend(imeiNo)
                    }
                    return false; // prevent the button click from happening
                }

            });

        });

        function calculate() {
            var productSubTotal = 0;

            var vat = $('#vat').val();
            var discount = $('#discount').val();
            var discount_in_amount = $('#discount_in_amount').val();
            var paid = $('#paid').val();

            if (vat == '' || vat < 0 || !$.isNumeric(vat))
                vat = 0;

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
            var productTotalVat = (productSubTotal * vat) / 100;
            var productTotalDiscount = (productSubTotal * discount) / 100;
            // var productTotalDiscountInAmount = (productSubTotal * discount_in_amount) / 100;
            $('#product-sub-total').html('৳' + productSubTotal.toFixed(2));
            $('#vat_total').html('৳' + productTotalVat.toFixed(2));
            $('#discount-amount').html('৳' + productTotalDiscount.toFixed(2));
            // $('#discount-in-amount').html('৳' + productTotalDiscountInAmount.toFixed(2));
            var total = parseFloat(productSubTotal) +
                parseFloat(productTotalVat)  -
                parseFloat(productTotalDiscount) - discount_in_amount;

            var due = parseFloat(total) - parseFloat(paid);
            $('#final-amount').html('৳' + total.toFixed(2));
            $('#due').html('৳' + due.toFixed(2));
            $('#total').val(total.toFixed(2));
            $('#due_total').val(due.toFixed(2));
        }
        function productAppend(imeiNo){

            $.ajax({
                method: "GET",
                url: "{{ route('sale_product.details') }}",
                data: {  imeiNo: imeiNo }
            }).done(function( response ) {
                if (response.success) {
                    if($.inArray(response.data.serial_no, ImeiSerials) != -1) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Already exist in list.',
                        });
                        $('#imei_no').val('');
                        return false;
                    }

                    var html = $('#template-product').html();
                    var itemHtml = $(html);

                    $('#product-container').append(itemHtml);

                    var item = $('.product-item').last();
                    item.hide();

                    item.closest('tr').find('.imei').val(response.data.serial_no);
                    item.closest('tr').find('.product_brand').val(response.data.brand ? response.data.brand.name : '');
                    item.closest('tr').find('.product_model').val(response.data.product_model ? response.data.product_model.name : '');
                    item.closest('tr').find('.product_type').val(response.data.product_type.imei === 1 ?  1 : 0);
                    item.closest('tr').find('.color').val(response.data.color ? response.data.color.name : '');
                    item.closest('tr').find('.quantity').val(1);
                    item.closest('tr').find('.quantity').attr({
                        "max" : response.count,
                        "min" : 1,
                    });
                    item.closest('tr').find('.quantity').prop("readonly",response.data.product_type.imei !== 1 ?  false : true);
                    item.closest('tr').find('.unit_price').val(response.data.selling_price-response.data.product_model.model_discount);
                    ImeiSerials.push(response.data.serial_no);

                    var addMoreSound = document.getElementById("add_more_sound");
                    addMoreSound.play();

                    item.show();
                    calculate();
                    $('#imei_no').val('');
                } else if (response.success == false) {
                    $('#imei_no').val('');
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: response.message,
                    });

                }
            });
        }
    </script>
@endsection
