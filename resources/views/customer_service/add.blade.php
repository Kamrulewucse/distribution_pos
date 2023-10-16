@extends('layouts.app')

@section('title')
    Customer Service Add
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Customer Service Information</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form class="form-horizontal" method="POST" action="{{ route('customer_service.add') }}">
                    @csrf

                    <div class="box-body">
                        <div class="form-group {{ $errors->has('name') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Name *</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter Name"
                                       name="name" value="{{ old('name') }}">

                                @error('name')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('mobile_name') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Mobile Model Name *</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter model Name"
                                       name="mobile_name" value="{{ old('mobile_name') }}">

                                @error('mobile_name')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('mobile_no') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Customer Mobile No. *</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter Mobile No."
                                       name="mobile_no" value="{{ old('mobile_no') }}">

                                @error('mobile_no')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('address') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Address</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter Address"
                                       name="address" value="{{ old('address') }}">

                                @error('address')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('date') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Receive Date</label>

                            <div class="col-sm-10">
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
                        <div class="form-group {{ $errors->has('delivery_date') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Delivery Date</label>

                            <div class="col-sm-10">
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control pull-right date-picker" id="delivery_date" name="delivery_date" value="{{ empty(old('delivery_date')) ? ($errors->has('delivery_date') ? '' : date('Y-m-d')) : old('delivery_date') }}" autocomplete="off">
                                </div>
                                <!-- /.input group -->

                                @error('delivery_date')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('note') ? 'has-error' :'' }}">
                            <label class="col-sm-2 control-label">Note</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="Enter Name"
                                       name="note" value="{{ old('note') }}">

                                @error('note')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

{{--                        <div class="form-group {{ $errors->has('status') ? 'has-error' :'' }}">--}}
{{--                            <label class="col-sm-2 control-label">Status</label>--}}

{{--                            <div class="col-sm-10">--}}

{{--                                <div class="radio" style="display: inline">--}}
{{--                                    <label>--}}
{{--                                        <input type="radio" name="status" value="1" {{ old('status') == '1' ? 'checked' : '' }}>--}}
{{--                                        Active--}}
{{--                                    </label>--}}
{{--                                </div>--}}

{{--                                <div class="radio" style="display: inline">--}}
{{--                                    <label>--}}
{{--                                        <input type="radio" name="status" value="0" {{ old('status') == '0' ? 'checked' : '' }}>--}}
{{--                                        Inactive--}}
{{--                                    </label>--}}
{{--                                </div>--}}

{{--                                @error('status')--}}
{{--                                <span class="help-block">{{ $message }}</span>--}}
{{--                                @enderror--}}
{{--                            </div>--}}
{{--                        </div>--}}



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
