@extends('layouts.app')

@section('style')
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('themes/backend/plugins/iCheck/square/blue.css') }}">
@endsection

@section('title')
    User Edit
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">User Information</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form class="form-horizontal" method="POST" action="{{ route('user.edit', ['user' => $user->id]) }}">
                    @csrf

                    <div class="box-body">
                        <div class="form-group {{ $errors->has('name') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Name *</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter Name"
                                       name="name" value="{{ empty(old('name')) ? ($errors->has('name') ? '' : $user->name) : old('name') }}">

                                @error('name')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('email') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Email *</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter Email"
                                       name="email" value="{{ empty(old('email')) ? ($errors->has('email') ? '' : $user->email) : old('email') }}">

                                @error('email')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('password') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Password</label>

                            <div class="col-sm-10">
                                <input type="password" class="form-control" placeholder="Enter Password"
                                       name="password">

                                @error('password')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Confirm Password</label>

                            <div class="col-sm-10">
                                <input type="password" class="form-control" placeholder="Enter Confirm Password"
                                       name="password_confirmation">
                            </div>
                        </div>

                        <table class="table table-bordered">
                            <tr>
                                <td colspan="2">
                                    <input type="checkbox" id="checkAll"> Check All
                                </td>
                            </tr>

                            <tr>
                                <td style="vertical-align: middle;">
                                    <input type="checkbox" name="permission[]" value="administrator" id="administrator" {{ ($user->can('administrator') ? 'checked' : '') }}> Administrator
                                </td>
                                <td>
                                    <input type="checkbox" name="permission[]" value="users" id="users" {{ ($user->can('users') ? 'checked' : '') }}> Users
                                </td>

                            </tr>

                            <tr>
                                <td rowspan="5" style="vertical-align: middle;">
                                    <input type="checkbox" name="permission[]" value="bank_and_account" id="bank_and_account" {{ ($user->can('bank_and_account') ? 'checked' : '') }}> Bank & Account
                                </td>

                                <td>
                                    <input type="checkbox" name="permission[]" value="bank" id="bank" {{ ($user->can('bank') ? 'checked' : '') }}> Bank
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="branch" id="branch" {{ ($user->can('branch') ? 'checked' : '') }}> Branch
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="account" id="account" {{ ($user->can('account') ? 'checked' : '') }}> Account
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="balance_transfer" id="balance_transfer" {{ ($user->can('balance_transfer') ? 'checked' : '') }}> Balance Transfer
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="bank_statement_report" id="bank_statement_report" {{ ($user->can('bank_statement_report') ? 'checked' : '') }}> Bank Statement Report
                                </td>
                            </tr>

                            <tr>
                                <td rowspan="10" style="vertical-align: middle;">
                                    <input type="checkbox" name="permission[]" value="purchase" id="purchase" {{ ($user->can('purchase') ? 'checked' : '') }}> Purchase
                                </td>
                                <td>
                                    <input type="checkbox" name="permission[]" value="warehouse" id="warehouse" {{ ($user->can('warehouse') ? 'checked' : '') }}> Warehouse
                                </td>
                            </tr>
                            <tr>

                                <td>
                                    <input type="checkbox" name="permission[]" value="supplier" id="supplier" {{ ($user->can('supplier') ? 'checked' : '') }}> Supplier
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="purchase_product" id="purchase_product" {{ ($user->can('purchase_product') ? 'checked' : '') }}> Product
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="purchase_product_category" id="purchase_product_category" {{ ($user->can('purchase_product_category') ? 'checked' : '') }}> Product Category
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="purchase_product_sub_category" id="purchase_product_sub_category" {{ ($user->can('purchase_product_sub_category') ? 'checked' : '') }}> Product Sub Category
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="purchase_order" id="purchase_order" {{ ($user->can('purchase_order') ? 'checked' : '') }}> Purchase Order
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="edit_purchase_order" id="edit_purchase_order" {{ ($user->can('edit_purchase_order') ? 'checked' : '') }}> Edit Purchase Order
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="purchase_receipt" id="purchase_receipt" {{ ($user->can('purchase_receipt') ? 'checked' : '') }}> Receipt
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="supplier_payment" id="supplier_payment" {{ ($user->can('supplier_payment') ? 'checked' : '') }}> Supplier Payment
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="purchase_inventory" id="purchase_inventory" {{ ($user->can('purchase_inventory') ? 'checked' : '') }}> Inventory
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="purchase_report" id="purchase_report" {{ ($user->can('purchase_report') ? 'checked' : '') }}> Purchase Report
                                </td>
                            </tr>

                            <tr>
                                <td rowspan="2" style="vertical-align: middle;">
                                    <input type="checkbox" name="permission[]" value="sale" id="sale" {{ ($user->can('sale') ? 'checked' : '') }}> Sale
                                </td>

                                <td>
                                    <input type="checkbox" name="permission[]" value="sales_order" id="sales_order" {{ ($user->can('sales_order') ? 'checked' : '') }}> Sales Order
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="sale_report" id="sale_report" {{ ($user->can('sale_report') ? 'checked' : '') }}> Sale Report
                                </td>
                            </tr>
                            <tr>
                                <td rowspan="2" style="vertical-align: middle;">
                                    <input type="checkbox" name="permission[]" value="wastage" id="wastage" {{ ($user->can('wastage') ? 'checked' : '') }}> Wastage
                                </td>

                                <td>
                                    <input type="checkbox" name="permission[]" value="wastage_order" id="wastage_order" {{ ($user->can('wastage_order') ? 'checked' : '') }}> Wastage Order
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="wastage_report" id="wastage_report" {{ ($user->can('wastage_report') ? 'checked' : '') }}> Wastage Report
                                </td>
                            </tr>

                            <tr>
                                <td rowspan="3" style="vertical-align: middle;">
                                    <input type="checkbox" name="permission[]" value="accounts" id="accounts" {{ ($user->can('accounts') ? 'checked' : '') }}> Daily Accounts
                                </td>

                                <td>
                                    <input type="checkbox" name="permission[]" value="account_head_type" id="account_head_type" {{ ($user->can('account_head_type') ? 'checked' : '') }}> Account Category
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="account_head_sub_type" id="account_head_sub_type" {{ ($user->can('account_head_sub_type') ? 'checked' : '') }}> Account Sub Category
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="transaction" id="transaction" {{ ($user->can('transaction') ? 'checked' : '') }}> Income/ Expense
                                </td>
                            </tr>


                            <tr>
                                <td rowspan="5" style="vertical-align: middle;">
                                    <input type="checkbox" name="permission[]" value="report" id="report" {{ ($user->can('report') ? 'checked' : '') }}> Report
                                </td>
                                <td>
                                    <input type="checkbox" name="permission[]" value="receive_and_payment_report" id="receive_and_payment_report" {{ ($user->can('receive_and_payment_report') ? 'checked' : '') }}> Receive & Payment Report
                                </td>

                            </tr>

                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="balance_sheet" id="balance_sheet" {{ ($user->can('balance_sheet') ? 'checked' : '') }}> Balance Sheet
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="profit_and_loss" id="profit_and_loss" {{ ($user->can('profit_and_loss') ? 'checked' : '') }}> Profit & Loss
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="ledger" id="ledger" {{ ($user->can('ledger') ? 'checked' : '') }}> Ledger
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <input type="checkbox" name="permission[]" value="transaction_report" id="transaction_report" {{ ($user->can('transaction_report') ? 'checked' : '') }}> Transaction Report
                                </td>
                            </tr>
                        </table>
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
    <!-- iCheck -->
    <script src="{{ asset('themes/backend/plugins/iCheck/icheck.min.js') }}"></script>
    <script>
        $(function () {
            $("#checkAll").click(function () {
                $('input:checkbox').not(this).prop('disabled', this.disabled);
                $('input:checkbox').not(this).prop('checked', this.checked);

                init();
            });

            // Administrator
            $('#administrator').click(function () {
                if ($(this).prop('checked')) {
                    $('#users').attr("disabled", false);
                } else {
                    $('#users').attr("disabled", true);
                }
            });

            // Bank & Account
            $('#bank_and_account').click(function () {
                if ($(this).prop('checked')) {
                    $('#bank').attr("disabled", false);
                    $('#branch').attr("disabled", false);
                    $('#account').attr("disabled", false);
                    $('#balance_transfer').attr("disabled", false);
                    $('#bank_statement_report').attr("disabled", false);
                } else {
                    $('#bank').attr("disabled", true);
                    $('#branch').attr("disabled", true);
                    $('#account').attr("disabled", true);
                    $('#balance_transfer').attr("disabled", true);
                    $('#bank_statement_report').attr("disabled", true);
                }
            });

            // Purchase
            $('#purchase').click(function () {
                if ($(this).prop('checked')) {
                    $('#warehouse').attr("disabled", false);
                    $('#supplier').attr("disabled", false);
                    $('#purchase_product').attr("disabled", false);
                    $('#purchase_product_category').attr("disabled", false);
                    $('#purchase_product_sub_category').attr("disabled", false);
                    $('#purchase_order').attr("disabled", false);
                    $('#edit_purchase_order').attr("disabled", false);
                    $('#purchase_receipt').attr("disabled", false);
                    $('#purchase_inventory').attr("disabled", false);
                    $('#supplier_payment').attr("disabled", false);
                    $('#purchase_report').attr("disabled", false);
                } else {
                    $('#warehouse').attr("disabled", true);
                    $('#supplier').attr("disabled", true);
                    $('#purchase_product_category').attr("disabled", true);
                    $('#purchase_product_sub_category').attr("disabled", true);
                    $('#purchase_product').attr("disabled", true);
                    $('#purchase_order').attr("disabled", true);
                    $('#edit_purchase_order').attr("disabled", true);
                    $('#purchase_receipt').attr("disabled", true);
                    $('#purchase_inventory').attr("disabled", true);
                    $('#supplier_payment').attr("disabled", true);
                    $('#purchase_report').attr("disabled", true);
                }
            });

            // Sale
            $('#sale').click(function () {
                if ($(this).prop('checked')) {
                    $('#customer').attr("disabled", false);
                    $('#sales_order').attr("disabled", false);
                    $('#sale_receipt').attr("disabled", false);
                    $('#product_sale_information').attr("disabled", false);
                    $('#customer_payment').attr("disabled", false);
                    $('#sale_report').attr("disabled", false);
                } else {
                    $('#customer').attr("disabled", true);
                    $('#sale_product').attr("disabled", true);
                    $('#sales_order').attr("disabled", true);
                    $('#sale_receipt').attr("disabled", true);
                    $('#product_sale_information').attr("disabled", true);
                    $('#customer_payment').attr("disabled", true);
                    $('#sale_report').attr("disabled", true);
                }
            });

            // Wastage
            $('#wastage').click(function () {
                if ($(this).prop('checked')) {
                    $('#wastage_order').attr("disabled", false);
                    $('#wastage_report').attr("disabled", false);
                } else {
                    $('#wastage_order').attr("disabled", true);
                    $('#wastage_report').attr("disabled", true);
                }
            });

            // Accounts
            $('#accounts').click(function () {
                if ($(this).prop('checked')) {
                    $('#account_head_type').attr("disabled", false);
                    $('#account_head_sub_type').attr("disabled", false);
                    $('#transaction').attr("disabled", false);
                } else {
                    $('#account_head_type').attr("disabled", true);
                    $('#account_head_sub_type').attr("disabled", true);
                    $('#transaction').attr("disabled", true);
                }
            });

            // Report
            $('#report').click(function () {
                if ($(this).prop('checked')) {
                    $('#receive_and_payment_report').attr("disabled", false);
                    $('#balance_sheet').attr("disabled", false);
                    $('#profit_and_loss').attr("disabled", false);
                    $('#ledger').attr("disabled", false);
                    $('#transaction_report').attr("disabled", false);
                } else {
                    $('#receive_and_payment_report').attr("disabled", true);
                    $('#balance_sheet').attr("disabled", true);
                    $('#profit_and_loss').attr("disabled", true);
                    $('#ledger').attr("disabled", true);
                    $('#transaction_report').attr("disabled", true);
                }
            });



            init();
        });

        function init() {
            if (!$('#administrator').prop('checked')) {
                $('#users').attr("disabled", true);
            }

            if (!$('#bank_and_account').prop('checked')) {
                $('#bank').attr("disabled", true);
                $('#branch').attr("disabled", true);
                $('#account').attr("disabled", true);
                $('#balance_transfer').attr("disabled", true);
                $('#bank_statement_report').attr("disabled", true);
            }

            if (!$('#purchase').prop('checked')) {
                $('#warehouse').attr("disabled", true);
                $('#supplier').attr("disabled", true);
                $('#purchase_product').attr("disabled", true);
                $('#purchase_product_category').attr("disabled", true);
                $('#purchase_product_sub_category').attr("disabled", true);
                $('#purchase_order').attr("disabled", true);
                $('#edit_purchase_order').attr("disabled", true);
                $('#purchase_receipt').attr("disabled", true);
                $('#purchase_inventory').attr("disabled", true);
                $('#supplier_payment').attr("disabled", true);
                $('#purchase_report').attr("disabled", true);

            }

            if (!$('#sale').prop('checked')) {
                $('#customer').attr("disabled", true);
                $('#sales_order').attr("disabled", true);
                $('#sale_receipt').attr("disabled", true);
                $('#product_sale_information').attr("disabled", true);
                $('#customer_payment').attr("disabled", true);
                $('#sale_report').attr("disabled", true);
            }

            if (!$('#wastage').prop('checked')) {
                $('#wastage_order').attr("disabled", true);
                $('#wastage_report').attr("disabled", true);
            }

            if (!$('#accounts').prop('checked')) {
                $('#account_head_type').attr("disabled", true);
                $('#account_head_sub_type').attr("disabled", true);
                $('#transaction').attr("disabled", true);

            }

            if (!$('#report').prop('checked')) {
                $('#receive_and_payment_report').attr("disabled", true);
                $('#balance_sheet').attr("disabled", true);
                $('#profit_and_loss').attr("disabled", true);
                $('#ledger').attr("disabled", true);
                $('#transaction_report').attr("disabled", true);
            }

        }
    </script>
@endsection
