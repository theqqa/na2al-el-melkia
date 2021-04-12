@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <h5 class="mb-0 h6">{{translate('Add New Representative')}}</h5>
</div>

<div class="col-lg-6 mx-auto">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{translate('Representative Information')}}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('representatives.store') }}" method="POST">
            	@csrf
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="name">{{translate('Name')}}</label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{translate('Name')}}" id="name" name="name" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" required>
                        @if ($errors->has('name'))
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="email">{{translate('Email')}}</label>
                    <div class="col-sm-9">
                        <input type="email" placeholder="{{translate('Email')}}" id="email" name="email" class="form-control" required>
                    </div>
                </div>
{{--                <div class="form-group row">--}}
{{--                    <label class="col-sm-3 col-from-label" for="email">{{translate('Code')}}</label>--}}
{{--                    <div class="col-sm-9">--}}
                        <input type="hidden"  id="code" name="code" class="form-control"  value="{{ \Illuminate\Support\Str::random(8) }}" readonly>
{{--                    </div>--}}
{{--                </div>--}}
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="email">{{translate('Transfer Price')}}</label>
                    <div class="col-sm-9">
                        <input type="number" placeholder="{{translate('Transfer Price')}}" id="transfer_price" name="transfer_price" class="form-control" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="password">{{translate('Renewal Price')}}</label>
                    <div class="col-sm-9">
                        <input type="number" placeholder="{{translate('Renewal Price')}}" id="renewal_price" name="renewal_price" class="form-control" required>
                    </div>
                </div>
{{--                <div class="form-group row">--}}
{{--                    <label class="col-sm-3 col-from-label" for="password">{{translate('Deserved Amount')}}</label>--}}
{{--                    <div class="col-sm-9">--}}
{{--                        <input type="number" placeholder="{{translate('Deserved Amount')}}" id="deserved_amount" name="deserved_amount" class="form-control" required>--}}
{{--                    </div>--}}
{{--                </div>--}}
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="email">{{translate('Initial Balance')}}</label>
                    <div class="col-sm-9">
                        <input type="number" placeholder="{{translate('Initial Balance')}}" id="initial_balance" name="initial_balance" class="form-control" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="email">{{translate('Register At')}}</label>
                    <div class="col-sm-9">
                        <input type="date" placeholder="{{translate('Register At')}}" id="register_at" name="register_at" class="form-control" required>
                    </div>
                </div>
                <div class="form-group mb-0 text-right">
                    <button type="submit" class="btn btn-primary">{{translate('Save')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
