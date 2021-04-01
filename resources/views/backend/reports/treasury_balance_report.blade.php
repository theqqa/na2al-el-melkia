@extends('backend.layouts.app')

@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class=" align-items-center">
       <h1 class="h3">{{translate('Treasury Balance Report')}}</h1>
	</div>
</div>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('treasury_balance_report.index') }}" method="GET">

                    <div class="card-header row gutters-5">
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
                        @if(!empty($business_settings))
                            <span class="btn-soft-success aiz-side-nav-text float-lg-right">{{__('Money')}} :{{$business_settings->value }} <span class="small">ريال</span></span>
                        @endif
                    </div>

</form>

<table class="table table-bordered  mb-0">
    <thead>
    <tr>
        <th data-breakpoints="lg">#</th>
        <th>{{translate('Date')}}</th>
        <th data-breakpoints="lg">{{ translate('Service') }}</th>
        <th data-breakpoints="lg">{{ translate('Balance Before') }}</th>
        <th data-breakpoints="lg">{{ translate('Balance Request') }}</th>
        <th data-breakpoints="lg">{{ translate('Balance After') }}</th>

    </tr>
    </thead>
    <tbody>
    @foreach($treasury_balances as $key => $treasury_balance)
        <tr>
            <td>{{ ($key+1) + ($treasury_balances->currentPage() - 1)*$treasury_balances->perPage() }}</td>
            <td>{{ date('Y-m-d', strtotime($treasury_balance->created_at)) }}</td>
            @if(!empty($treasury_balance->catch_receipt_id))

                <td><a href="{{route('catch_receipts.edit',$treasury_balance->catchReceipt->id )}}">{{__('View Catch Receipt')}}</a></td>
            @elseif(!empty($treasury_balance->permission_exchange_id))
                <td><a href="{{route('permission_exchanges.show',$treasury_balance->permission_exchange_id)}}">{{__('View Transaction')}}</a></td>
            @endif
            <td>{{ $treasury_balance->balance_before}}</td>
            <td>{{ $treasury_balance->balance_request }}</td>
            <td>{{ $treasury_balance->balance_after}}</td>


        </tr>
    @endforeach
    </tbody>
</table>
                <div class="aiz-pagination mt-4">
                    {{ $treasury_balances->links() }}
                </div>
</div>
</div>
</div>
</div>

@endsection
