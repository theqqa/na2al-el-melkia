@extends('backend.layouts.app')

@section('content')

<div class="row">
    <div class="col-lg-10 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{translate('Catch Receipt Information')}}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('catch_receipts.store') }}" method="POST">
                    @csrf
                    <div class="form-group row">
                        <label class="col-sm-3 control-label" for="name">{{translate('Price')}}</label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="{{translate('Price')}}" id="price" name="price" class="form-control" required>
                        </div>
                    </div>
{{--                    <input type="hidden"  id="code" name="code" class="form-control"  value="{{'Cr-'.\Illuminate\Support\Str::random(2) }}" readonly>--}}

                    <div class="form-group row">
                        <label class="col-sm-3 control-label" for="name">{{translate('Date')}}</label>
                        <div class="col-sm-9">
                            <input type="date" placeholder="{{translate('Date')}}" id="date" name="date" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 control-label" for="background_color">{{translate('Representative')}} </label>
                        <div class="col-sm-9">
                            <select class="form-control aiz-selectpicker" name="representative_id" id="representative_id" data-live-search="true" required>
                                @foreach ($rep_lists as $val)
                                    <option value="{{ $val->id }}">{{ $val->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 control-label" for="name">{{translate('Payment By')}}</label>
                        <div class="col-sm-9">
                            <select class="form-control aiz-selectpicker" name="payment_by" id="payment_by" data-live-search="true" required>
                                <option value="1">{{ translate('cache')}}</option>
                                <option value="2">{{ translate('Bank transfer')}}</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-3 col-from-label">{{translate('Description')}}</label>
                        <div class="col-lg-9">
                            <textarea name="description" rows="5" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="form-group mb-0 text-right">
                        <button type="submit"   class="btn btn-primary">{{translate('Save')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
    <script type="text/javascript">
        $(document).ready(function(){

        });
    </script>
@endsection
