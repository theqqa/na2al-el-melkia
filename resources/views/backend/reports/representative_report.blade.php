@extends('backend.layouts.app')

@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class=" align-items-center">
       <h1 class="h3">{{translate('User Transaction Report')}}</h1>
	</div>
</div>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('users_representative_report.index') }}" method="GET">




                    <div class="card-header row gutters-5">
                        <div class="col text-center text-md-left">
                            <h5 class="mb-md-0 h6">{{ translate('Sort by Representative') }}</h5>
                        </div>

                            <div class="col-md-3 ml-auto">
                                <select class="from-control aiz-selectpicker" name="rep_id" required >
                                    <option value="null" @if(empty($rep_id ) )selected @endif>{{ __('All') }}</option>
                                    @foreach($rep as $key=>$val)
                                        <option value="{{$val->id}}" @if($rep_id == $val->id) selected @endif>{{ $val->name }}</option>
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
                    @if(!empty($rep_id) && $rep_id != null)

                    <div class="card-header row ">
                      <?php   $rep =   \App\Models\Representative::find($rep_id); ?>
                      <span class="btn-soft-success aiz-side-nav-text">{{translate('Representative Name')}} :{{$rep->name }} <span class="small">ريال</span></span>
                          <span class="btn-soft-info aiz-side-nav-text">{{translate('Representative Code')}} :{{$rep->code }} <span class="small">ريال</span></span>


</div>

                        <br>
                            <?php   $rep =   \App\Models\Representative::find($rep_id); ?>
                            <h5 class="mb-md-0 h6 col-6 ">{{translate('Representative Code')}} : {{ $rep->code }}</h5>
                                <h5 class="mb-md-0 h6 col-6 ">{{translate('Representative Name')}} : {{ $rep->name }}</h5>
                                <h5 class="mb-md-0 h6 col-6 ">{{translate('Initial Balance')}} : {{ $rep->initial_balance }} <span class="small">ريال</span></h5>
                                <h5 class="mb-md-0 h6 col-6 ">{{translate('Required Balance')}} : {{ $rep->deserved_amount }} <span class="small">ريال</span></h5>
                                <h5 class="mb-md-0 h6 col-6 ">{{translate('Paid Balance')}} : {{ $paid_hist }} <span class="small">ريال</span></h5>

<br>
                        @endif

</form>

<table class="table table-bordered  mb-0">
<thead>
  <tr>
    <th>#</th>
      <th data-breakpoints="lg">{{ translate('Time Date') }}</th>
      <th data-breakpoints="lg">{{ translate('Representative Name') }}</th>
      <th>{{translate('Service Type')}}</th>
      <th>{{translate('Transactions Id').'/'.translate('Code Catch receipts')}}</th>
{{--      <th data-breakpoints="lg">{{ translate('Price Before') }}</th>--}}
      <th data-breakpoints="lg">{{ translate('Price') }}</th>
      <th data-breakpoints="lg">{{ translate('Price After') }}</th>
</thead>
<tbody>
  @foreach ($rep_hists as $key => $val)

          <tr>
              <td>{{$key+1}}</td>
              <td>{{ $val->created_at }}</td>

              <td >{{ $val->representative->name }}</td>
              @if(!empty($val->catch_receipt_id))
              <td>
                  <a href="{{route('catch_receipts.edit',$val->catchReceipt->id )}}">{{translate('Catch Receipt')}}</a></td>
              @elseif(!empty($val->transaction_id))
                  @if ($val->transaction->type==1)

                      <td><a href="{{route('transactions.index')}}">{{translate('Ownership')}}</a></td>

                  @elseif($val->transaction->type==2)

                      <td><a href="{{route('transactions.index')}}">{{translate('Renewal')}}</a></td>

                  @elseif($val->transaction->type==3)

              <td><a href="{{route('transactions.index')}}">{{translate('Both')}}</a></td>
                  @endif

              @endif
              @if(!empty($val->catch_receipt_id))
              <td>{{$val->catchReceipt->code}}</td>
              @elseif(!empty($val->transaction_id))
                  <td>{{$val->transaction->transaction_id}}</td>
              @endif
{{--              <td>{{ $val->deserved_amount_before}}</td>--}}
              @if(!empty($val->catch_receipt_id))
              <td style="color: red">{{ '- ' .$val->deserved_amount_request}}</td>
              @elseif(!empty($val->transaction_id))
                  <td>{{ $val->deserved_amount_request}}</td>

              @endif

              <td>{{ $val->deserved_amount_after}}</td>
          </tr>
          @if($loop->last)
              <tr style="font-weight: bold">
                  <td colspan="5">{{translate('Ownership Count')}}</td>
                  <td colspan="2" class="btn-soft-success aiz-side-nav-text"> <span class="small">ريال</span> </td>

              </tr>
              <tr style="font-weight: bold">
                  <td colspan="5">{{translate('Renewal Count')}}</td>
                  <td colspan="2" class="btn-soft-success aiz-side-nav-text"> <span class="small">ريال</span> </td>

              </tr>
              <tr style="font-weight: bold">
                  <td colspan="5">{{translate('Total2')}}</td>
                  <td colspan="2" class="btn-soft-success aiz-side-nav-text"> <span class="small">ريال</span> </td>

              </tr>
              <tr style="font-weight: bold">
                  <td colspan="5">{{translate('Tax')}}</td>
                  <td colspan="2" class="btn-soft-success aiz-side-nav-text"> <span class="small">15%</span> </td>

              </tr>
              <tr style="font-weight: bold">
                  <td colspan="5">{{translate('Total')}}</td>
                  <td colspan="2" class="btn-soft-success aiz-side-nav-text"> <span class="small">ريال</span> </td>

              </tr>
          @endif
  @endforeach

</tbody>
</table>
                <div class="aiz-pagination mt-4">
                    {{ $rep_hists->links() }}
                </div>
</div>
</div>
</div>
</div>

@endsection
