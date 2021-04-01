@extends('backend.layouts.app')

@section('content')

    <div class="row">
        <div class="col-lg-6">
            <div class="card">
              <div class="card-header">
                  <h5 class="mb-0 h6">{{translate('Renewal Taam Expenses')}}</h5>
              </div>
              <div class="card-body">
                  <form class="form-horizontal" action="{{ route('business_settings.taam_expenses.update') }}" method="POST" enctype="multipart/form-data">
                  	@csrf
                    <div class="form-group row">
                        <input type="hidden" name="type" value="{{ $business_settings_renewal->type }}">
                        <label class="col-lg-3 control-label">{{ translate('Taam Expenses') }}</label>
                        <div class="col-lg-9">
                            <div class="input-group">
                                <input type="number" lang="en" min="0" step="0.01" value="{{ $business_settings_renewal->value }}" placeholder="{{translate('Taam Expenses')}}" name="value" class="form-control">
                                <div class="input-group-append">
                                    <span class="input-group-text">SR</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-primary">{{translate('Save')}}</button>
                    </div>
                  </form>
              </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{translate('Ownership Taam Expenses')}}</h5>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('business_settings.taam_expenses.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row">
                            <input type="hidden" name="type" value="{{ $business_settings_ownership->type }}">
                            <label class="col-lg-3 control-label">{{ translate('Taam Expenses') }}</label>
                            <div class="col-lg-9">
                                <div class="input-group">
                                    <input type="number" lang="en" min="0" step="0.01" value="{{ $business_settings_ownership->value }}" placeholder="{{translate('Taam Expenses')}}" name="value" class="form-control">
                                    <div class="input-group-append">
                                        <span class="input-group-text">SR</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-primary">{{translate('Save')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
