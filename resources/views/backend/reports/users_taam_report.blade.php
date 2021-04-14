@extends('backend.layouts.app')

@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class=" align-items-center">
       <h1 class="h3">{{translate('User Transaction Taam Report')}}</h1>
	</div>
</div>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('users_taam_report.index') }}" method="GET">
                    <div class="card-header row gutters-5">
                        <div class="col text-center text-md-left">
                            <h5 class="mb-md-0 h6">{{ translate('Sort by User') }}</h5>
                        </div>

                            <div class="col-md-3 ml-auto">
                                <select class="from-control aiz-selectpicker" name="user_id" required>
                                    <option value="null" @if(empty($user_id ) )selected @endif>{{ translate('All') }}</option>
                                    @foreach($users as $key=>$val)
                                        <option value="{{$val->user->id}}" @if($user_id == $val->user->id) selected @endif>{{ $val->user->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                        <div class="col-md-3">
                            <div class="form-group mb-0">
                                <input type="text" class="form-control form-control-sm aiz-date-range" id="search" name="date_range"@isset($date_range) value="{{ $date_range }}" @endisset placeholder="{{ translate('Daterange') }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-md btn-primary" type="submit">
                                {{ translate('Filter') }}
                            </button>
                        </div>
                    </div>

                    <div class="card-header row ">
                        @if(!empty($business_settings_renewal) && !empty($business_settings_ownership))
                            <span class="btn-soft-success aiz-side-nav-text">{{translate('TAAM Renewal Service')}} :{{$business_settings_renewal->value }} <span class="small">ريال</span></span>
                            <span class="btn-soft-info aiz-side-nav-text">{{translate('TAAM Ownership Service')}} :{{$business_settings_ownership->value }} <span class="small">ريال</span></span>

                        @endif
                    </div>
                </form>

                <table class="table table-bordered  mb-0">
                    <thead>
                        <tr>
{{--                          <th>#</th>--}}
                            <th>{{ translate('Service Name') }}</th>
                            <th data-breakpoints="lg">{{ translate('Count') }}</th>
{{--                            <th data-breakpoints="lg">{{ translate('Price') }}</th>--}}
                            <th data-breakpoints="lg">{{ translate('Cost') }}</th>
                    </thead>
                    <tbody>
                    <tr>
                        <td>  {{translate("Ownership")}}</td>
                        <td>  {{ $transaction_owner_count }}</td>
                        <td>  {{ $transaction_owner_count * (int)$business_settings_ownership->value + 0.15*($transaction_owner_count * (int)$business_settings_ownership->value) }}</td>
                    </tr>
                    <tr>
                        <td>  {{translate("Renewal")}}</td>
                        <td>  {{ $transaction_renewal_count }}</td>
                        <td>  {{ $transaction_renewal_count*(int)$business_settings_renewal->value +0.15*($transaction_renewal_count*(int)$business_settings_renewal->value ) }}</td>
                    </tr>

                    </tbody>
                </table>
                <div class="aiz-pagination mt-4">
{{--                    {{ $transactions->links() }}--}}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
