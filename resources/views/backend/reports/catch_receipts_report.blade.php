@extends('backend.layouts.app')

@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class=" align-items-center">
       <h1 class="h3">{{translate('Catch Receipts Report')}}</h1>
	</div>
</div>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('catch_receipts_report.index') }}" method="GET">




                    <div class="card-header row gutters-5">
                        <div class="col text-center text-md-left">
                            <h5 class="mb-md-0 h6">{{ translate('Sort by Representative') }}</h5>
                        </div>

                            <div class="col-md-3 ml-auto">
                                <select class="from-control aiz-selectpicker" name="rep_id" required>
                                    <option value="null" @if(empty($rep_id ) )selected @endif>{{ __('All') }}</option>
                                    @foreach($rep as $key=>$val)
                                        <option value="{{$val->id}}" @if($rep_id == $val->id) selected @endif>{{ $val->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                        <div class="col-md-3">
                            <div class="form-group mb-0">
                                <input type="text" class="form-control form-control-sm aiz-date-range" id="search" name="date_range" autocomplete="off" @isset($date_range) value="{{ $date_range }}" @endisset placeholder="{{ translate('Daterange') }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-md btn-primary" type="submit">
                                {{ translate('Filter') }}
                            </button>
                        </div>
                    </div>
                    <div class="card-header row ">
                    @if(!empty($rep_id) && $rep_id != null)
                      <?php   $rep =   \App\Models\Representative::find($rep_id); ?>
                      <span class="btn-soft-success aiz-side-nav-text">{{translate('Transfer Price Service')}} :{{$rep->transfer_price }} <span class="small">ريال</span></span>
                          <span class="btn-soft-info aiz-side-nav-text">{{translate('Renewal Price Service')}} :{{$rep->renewal_price }} <span class="small">ريال</span></span>
                          <span class="btn-soft-danger aiz-side-nav-text">{{translate('Deserved Amount')}} :{{$rep->deserved_amount }} <span class="small">ريال</span></span>

                        @endif
</div>
</form>

<table class="table table-bordered  mb-0">
    <thead>
    <tr>
        <th data-breakpoints="lg">#</th>
        <th>{{translate('Date')}}</th>
        <th data-breakpoints="lg">{{ translate('Representative Name') }}</th>
        <th data-breakpoints="lg">{{ translate('Price') }}</th>
        <th data-breakpoints="lg">{{ translate('Payment by') }}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($catch_receipts as $key => $catch_receipt)
        <tr>
            <td>{{ ($key+1) + ($catch_receipts->currentPage() - 1)*$catch_receipts->perPage() }}</td>
            <td>{{ date('Y-m-d', strtotime($catch_receipt->date)) }}</td>
            <td>{{ $catch_receipt->representative->name }}</td>
            <td>{{ $catch_receipt->price}}</td>
            <td>{{ ($catch_receipt->payment_by==1)?translate('Cache') :translate('Bank transfer') }}</td>

        </tr>
{{--        @if(!empty($rep_id) && $rep_id != null)--}}
        @if($loop->last)
            <tr style="font-weight: bold">>
                <td colspan="3">{{translate('Total2')}}</td>
                <td colspan="2" class="btn-soft-success aiz-side-nav-text">{{$total_price}} <span class="small">ريال</span> </td>

            </tr>
            @endif
{{--        @endif--}}
    @endforeach
    </tbody>
</table>
                <div class="aiz-pagination mt-4">
                    {{ $catch_receipts->links() }}
                </div>
</div>
</div>
</div>
</div>

@endsection
