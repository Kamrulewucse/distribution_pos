@extends('layouts.app')

@section('style')
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
@endsection

@section('title')
    Ledger
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Filter</h3>
                </div>
                <!-- /.box-header -->

                <div class="box-body">
                    <form action="{{ route('report.ledger') }}">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Start Date</label>

                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control pull-right"
                                               id="start" name="start" value="{{ request()->get('start')  }}" autocomplete="off" required>
                                    </div>
                                    <!-- /.input group -->
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>End Date</label>

                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control pull-right"
                                               id="end" name="end" value="{{ request()->get('end')  }}" autocomplete="off" required>
                                    </div>
                                    <!-- /.input group -->
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Account Head</label>
                                    <div class="input-group">
                                        <select class="form-control" name="accounthead" id="accounthead">
                                            <option>Select Account Head</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Account Sub Head</label>
                                    <div class="input-group">
                                        <select class="form-control" name="accountsubhead" id="accountsubhead">
                                            <option>Select Account Sub Head</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>	&nbsp;</label>

                                    <input class="btn btn-primary form-control" type="submit" value="Submit">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12" style="min-height:300px">
            @if($incomes)
                <section class="panel">

                    <div class="panel-body">
                        <button class="pull-right btn btn-primary" onclick="getprint('prinarea')">Print</button><br><hr>
                        <div class="adv-table" id="prinarea">
                            <table class="table table-bordered" style="margin-bottom: 0px">
                                <tr>
                                    <th class="text-center">{{ date("F d, Y", strtotime(request()->get('start'))).' - '.date("F d, Y", strtotime(request()->get('end'))) }}</th>
                                </tr>
                            </table>
                            <div style="clear: both">
                                <table class="table table-bordered" style="width:50%; float:left">
                                    <tr>
                                        <th colspan="6" class="text-center">Credit</th>
                                    </tr>
                                    <tr>
                                        <th width="10%">Date</th>
                                        <th width="25%">Particular</th>
                                        <th width="20%">Note</th>
                                        <th width="15%">Payment Type</th>
                                        <th width="20%">Bank Details</th>
                                        <th width="10%">Amount</th>
                                    </tr>
                                    @foreach($incomes as $income)
                                        <tr>
                                            <td>{{ $income->date->format('j F, Y') }}</td>
                                            <td>{{ $income->particular }}</td>
                                            <td>{{ $income->note }}</td>
                                            <td>
                                                @if($income->transaction_method == 1)
                                                    Cash
                                                @elseif($income->transaction_method == 2)
                                                    Bank
                                                @elseif($income->transaction_method == 3)
                                                    Mobile Banking
                                                @endif
                                            </td>
                                            <td>
                                                @if ($income->transaction_method == 2)
                                                    {{ $income->bank->name.' - '.$income->account->account_no }}
                                                @endif
                                            </td>
                                            <td align="right">{{ $income->amount }}</td>
                                        </tr>
                                    @endforeach
                                    <?php
                                    $incomesCount = count($incomes);
                                    $expensesCount = count($expenses);

                                    if ($incomesCount > $expensesCount)
                                        $maxCount = $incomesCount;
                                    else
                                        $maxCount = $expensesCount;

                                    $maxCount += 2;
                                    ?>

                                    @for($i=count($incomes); $i<$maxCount; $i++)
                                        <tr>
                                            <td><br><br></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    @endfor

                                    <tr>
                                        <td colspan="5">Total</td>
                                        <td align="right">{{ $incomes->sum('amount') }}</td>
                                    </tr>
                                </table>
                                <table class="table table-bordered" style="width:50%; float:left">
                                    <tr>
                                        <th colspan="6" class="text-center">Debit</th>
                                    </tr>
                                    <tr>
                                        <th width="10%">Date</th>
                                        <th width="25%">Particular</th>
                                        <th width="20%">Note</th>
                                        <th width="15%">Payment Type</th>
                                        <th width="20%">Bank Details</th>
                                        <th width="10%">Amount</th>
                                    </tr>

                                    @foreach($expenses as $expense)
                                        <tr>
                                            <td>{{ $expense->date->format('j F, Y') }}</td>
                                            <td>{{ $expense->particular }}</td>
                                            <td>{{ $expense->note }}</td>
                                            <td>
                                                @if($expense->transaction_method == 1)
                                                    Cash
                                                @elseif($expense->transaction_method == 2)
                                                    Bank
                                                @elseif($expense->transaction_method == 3)
                                                    Mobile Banking
                                                @endif
                                            </td>
                                            <td>
                                                @if ($expense->transaction_method == 2)
                                                    {{ $expense->bank->name.' - '.$expense->account->account_no }}
                                                @endif
                                            </td>
                                            <td align="right">{{ $expense->amount }}</td>
                                        </tr>
                                    @endforeach

                                    @for($i=count($expenses); $i<$maxCount; $i++)
                                        <tr>
                                            <td><br><br></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    @endfor

                                    <tr>
                                        <td colspan="5">Total</td>
                                        <td align="right">{{ $expenses->sum('amount') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </section>
            @endif
        </div>
    </div>
@endsection

@section('script')
    <!-- bootstrap datepicker -->
    <script src="{{ asset('themes/backend/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>

    <script>
        $(function () {
            //Date picker
            $('#start, #end').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd'
            });
        });

        var APP_URL = '{!! url()->full()  !!}';
        function getprint(print) {

            $('body').html($('#'+print).html());
            window.print();
            window.location.replace(APP_URL)
        }
    </script>
    <script>
        $(function () {
            var TypeSelected = '{{ old('type') }}';
            var SubTypeSelected = '{{ old('accounthead') }}';

            $('#type').change(function () {
                var type = $(this).val();
                $('#accounthead').html('<option value="">Select Account Head </option>');

                if (type != '') {
                    $.ajax({
                        method: "GET",
                        url: "{{ route('get_account_head_type') }}",
                        data: { type: type }
                    }).done(function( data ) {
                        $.each(data, function( index, item ) {
                            if (TypeSelected == item.id)
                                $('#accounthead').append('<option value="'+item.id+'" selected>'+item.name+'</option>');
                            else
                                $('#accounthead').append('<option value="'+item.id+'">'+item.name+'</option>');
                        });

                        $('#accounthead').trigger('change');
                    });
                }
            });

            $('#type').trigger('change');

        $('#accounthead').change(function () {
            var typeId = $(this).val();

            $('#accountsubhead').html('<option value="">Select Account Sub Head </option>');

            if (typeId != '') {
                $.ajax({
                    method: "GET",
                    url: "{{ route('get_account_head_sub_type') }}",
                    data: { typeId: typeId }
                }).done(function( data ) {
                    $.each(data, function( index, item ) {
                        if (SubTypeSelected == item.id)
                            $('#accountsubhead').append('<option value="'+item.id+'" selected>'+item.name+'</option>');
                        else
                            $('#accountsubhead').append('<option value="'+item.id+'">'+item.name+'</option>');
                    });
                });
            }
        });
        });
    </script>
@endsection
