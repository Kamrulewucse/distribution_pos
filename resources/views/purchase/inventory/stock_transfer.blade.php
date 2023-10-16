@extends('layouts.app')

@section('title')
    Stock Transfer
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


    <form  method="POST" enctype="multipart/form-data" action="{{ route('purchase_stock_transfer') }}">
        @csrf

        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Warehouse Information</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group {{ $errors->has('source_warehouse') ? 'has-error' :'' }}">
                                    <label>From Godown </label>
                                    <select name="source_warehouse" class="form-control select2" id="source_warehouse">
                                        <option value="">Select Godown</option>
                                        @foreach($source_warehouses as $warehouse)
                                            <option value="{{ $warehouse->id }}" {{ old('source_warehouse') == $warehouse->id ? 'selected' : '' }}>{{ $warehouse->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('source_warehouse')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group {{ $errors->has('target_shop') ? 'has-error' :'' }}">
                                    <label>To Shop </label>
                                    <select name="target_shop" class="form-control select2" id="target_shop">
                                        <option value="">Select Shop</option>
                                        @foreach($target_shops as $target_shop)
                                            <option value="{{ $target_shop->id }}" {{ old('target_shop') == $target_shop->id ? 'selected' : '' }}>{{ $target_shop->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('target_shop')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4"></div>
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
                                    <input autocomplete="off" type="text" id="imei_no" class="form-control" placeholder="Enter IMEI No/Model No">
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
                                    <th width="10%">Available Stock</th>
                                    <th width="11%">Transfer Quantity</th>
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
                                                <div class="form-group {{ $errors->has('available_qty.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="text" class="form-control available_qty" name="available_qty[]" value="{{ old('available_qty.'.$loop->index) }}" readonly>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group {{ $errors->has('transfer_quantity.'.$loop->index) ? 'has-error' :'' }}">
                                                    <input type="hidden"  class="product_type" name="product_type[]" value="{{ old('product_type.'.$loop->index) }}">
                                                    <input {{ old('product_type.'.$loop->index) == 1 ? 'readonly' : '' }} type="number" step="any" class="form-control transfer_quantity" name="transfer_quantity[]" value="{{ old('transfer_quantity.'.$loop->index) }}">
                                                </div>
                                            </td>

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
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
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
                    <input type="text" class="form-control available_qty" name="available_qty[]" readonly>
                </div>
            </td>

            <td>
                <div class="form-group">
                    <input type="hidden"  class="product_type" name="product_type[]">
                    <input type="number" readonly step="any" class="form-control transfer_quantity" name="transfer_quantity[]">
                </div>
            </td>

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

                if ($('.product-item').length<= 1 ){
                    $('.btn-remove').hide();
                }
                ImeiSerials = $.grep(ImeiSerials, function(value) {
                    return value != imei;
                });
            });

            $('body').on('keyup', '.transfer_quantity, .available_qty,  #vat, #discount, #paid', function () {
                calculate();
            });

            $('body').on('change', '.transfer_quantity, .available_qty',  function () {
                calculate();
            });

            if ($('.product-item').length.length <= 1 ) {
                $('.btn-remove').hide();
            } else {
                $('.btn-remove').show();
            }

            calculate();

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

        function productAppend(imeiNo){

            $.ajax({
                method: "GET",
                url: "{{ route('transfer_product.details') }}",
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
                    item.closest('tr').find('.available_qty').val(1);
                    item.closest('tr').find('.transfer_quantity').val(1);
                    item.closest('tr').find('.transfer_quantity').attr({
                        "max" : response.count,
                        "min" : 1,
                    });
                    item.closest('tr').find('.transfer_quantity').prop("readonly",response.data.product_type.imei !== 1 ?  false : true);
                    item.closest('tr').find('.available_qty').val(response.data.quantity);
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
        function calculate() {
            var productSubTotal = 0;

            $('.product-item').each(function(i, obj) {
                var transfer_quantity = $('.transfer_quantity:eq('+i+')').val();
                // var available_qty = $('.available_qty:eq('+i+')').val();

                if (transfer_quantity == '' || transfer_quantity < 0 || !$.isNumeric(transfer_quantity))
                    transfer_quantity = 0;

                productSubTotal += transfer_quantity;
            });

        }
    </script>
@endsection
