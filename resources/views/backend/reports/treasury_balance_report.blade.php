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
                        <div class="col-md-3 ml-auto">
                            <select class="from-control aiz-selectpicker" name="expense_by" data-live-search="true"  required >
                                <option value="null" @if(empty( $expense_by) )selected @endif>{{ translate('Expense By') }}</option>
                                <option value="1" @if(!empty( $expense_by) && $expense_by==1 )selected @endif>{{ translate('cache')}}</option>
                                <option value="2" @if(!empty( $expense_by) && $expense_by==2 )selected @endif>{{ translate('Bank transfer')}}</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-md btn-primary" type="submit">
                                {{ translate('Filter') }}
                            </button>
                        </div>
                    </div>


</form>
                <div id="divName">
                    <div class="card-header row ">
                        @if(!empty($business_settings))
                            <span class="btn-soft-success aiz-side-nav-text float-lg-right">{{translate('Initial Balance')}} : {{ $business_settings_initial->value }} <span class="small">ريال</span></span>

                            <span class="btn-soft-success aiz-side-nav-text float-lg-right">{{translate('Total Balance')}} :{{$business_settings->value }} <span class="small">ريال</span></span>
                        @endif
                    </div>
<table class="table table-bordered  mb-0">
    <thead>
    <tr>
        <th data-breakpoints="lg">#</th>
        <th>{{translate('Date')}}</th>
        <th data-breakpoints="lg">{{ translate('Service') }}</th>
        <th data-breakpoints="lg">{{ translate('Statement') }}</th>
        <th data-breakpoints="lg">{{ translate('Debtor') }}</th>
        <th data-breakpoints="lg">{{ translate('The creditor') }}</th>
        <th data-breakpoints="lg">{{ translate('Balance') }}</th>

    </tr>
    </thead>
    <tbody>
    <?php
    $total_catach =0;
    $total_permission=0;
    ?>
    @foreach($treasury_balances as $key => $treasury_balance)
        <tr>
            <td>{{ $key+1 }}</td>
            <td>{{ date('Y-m-d', strtotime($treasury_balance->created_at)) }}</td>
            @if(!empty($treasury_balance->catch_receipt_id))

                <td><a href="{{route('catch_receipts.edit',$treasury_balance->catchReceipt->id )}}">{{translate('Catch Receipt').' - '.$treasury_balance->catchReceipt->code}}</a></td>
            @elseif(!empty($treasury_balance->permission_exchange_id))
                <td><a href="{{route('permission_exchanges.show',$treasury_balance->permission_exchange_id)}}">{{translate('Permission Exchanges')}}</a></td>
            @endif
            @if(!empty($treasury_balance->catch_receipt_id))
                <?php
                $total_catach +=$treasury_balance->balance_request;
                ?>
                <td><a href="{{route('representatives.show', encrypt( $treasury_balance->catchReceipt->representative->id))}}">{{ $treasury_balance->catchReceipt->representative->name}}</a></td>
                <td>{{ $treasury_balance->balance_request }}</td>
                <td>--</td>
            @elseif(!empty($treasury_balance->permission_exchange_id))
                <?php
                $total_permission+=$treasury_balance->balance_request;
                ?>
                <td>{{ $treasury_balance->permissionExchange->expense->name}}</td>
                <td>--</td>
                <td style="color: red">{{- $treasury_balance->balance_request }}</td>
            @endif
            <td>{{ $treasury_balance->balance_after + $changed_treasury}}</td>


        </tr>
        @if($loop->last)
            <tr style="font-weight: bold">
                <td colspan="4">{{translate('Total2')}}</td>
                <td colspan="1" class="btn-soft-success aiz-side-nav-text"> <span class="small">ريال {{$total_catach}}</span> </td>
                <td colspan="1" class="btn-soft-danger aiz-side-nav-text"> <span class="small">ريال {{$total_permission}}</span> </td>

            </tr>
        @endif
    @endforeach
    </tbody>
</table>
                    <button class="btn btn-md btn-success "  onClick="printDiv();" id="print_id">   <span  class=" las la-print"> {{ translate('Print') }}</span> </button>

                </div>
{{--                <div class="aiz-pagination mt-4">--}}
{{--                    {{ $treasury_balances->links() }}--}}
{{--                </div>--}}
</div>
</div>
</div>
</div>

@endsection
@section('script')
    <script type="text/javascript">
        function printDiv() {
            var printContents = document.getElementById("divName").innerHTML;
            var originalContents = document.body.innerHTML;
            document.getElementById('sidenav').style.display = 'none';
            document.getElementById('nav').style.display = 'none';

            document.body.innerHTML = printContents;

            window.print();


            document.body.innerHTML = originalContents;
        }

    </script>
@endsection
