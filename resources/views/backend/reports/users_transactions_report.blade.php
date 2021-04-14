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
                <form action="{{ route('users_transactions_report.index') }}" method="GET">




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
                </form>
                <div id="divName">
                <div class="card-header row ">

{{--                        <span class="btn-soft-success aiz-side-nav-text">{{translate('Initial Treasury Balance')}} :{{$initial_treasury_balance }} <span class="small">ريال</span></span>--}}
                    <span class="btn-soft-info aiz-side-nav-text">{{translate('Previse Balance')}} :{{$total_pre }} <span class="small">ريال</span></span>

                </div>
                <table class="table table-bordered  mb-0">
                    <thead>
                        <tr>
                            <th data-breakpoints="lg">{{ translate('Time Date') }}</th>

                            <th>{{ translate('User Name') }}</th>
                            <th data-breakpoints="lg">{{ translate('Representative Name') }}</th>
                            <th>{{translate('Transactions Id')}}</th>
                            <th data-breakpoints="lg">{{ translate('Type') }}</th>
                            <th data-breakpoints="lg">{{ translate('Price') }}</th>

                            <th data-breakpoints="lg" >{{ translate('Total') }}</th>
                    </thead>
                    <tbody>

                            @foreach ($transactions as $key => $transaction)

                                <tr>
                                    <td >{{ date('Y-m-d H:i:s', strtotime($transaction->timedate)) }}</td>

                                    <td >{{ $transaction->user->name }}</td>
                                    <td >{{$transaction->representative->name}}</td>
                                    <td>{{ $transaction->transaction_id}}</td>

                                    @if ($transaction->type==1)
                                    <td>
                                        {{translate("Ownership")}}
                                    </td>
                                        <td>
                                            {{$transaction->representative->transfer_price}}
                                        </td>
                                    @elseif($transaction->type==2)
                                     <td>
                                            {{translate("Renewal")}}
                                    </td>
                                        <td>
                                            {{$transaction->representative->renewal_price}}
                                        </td>
                                    @elseif($transaction->type==3)
                                        <td>
                                            {{translate("Both")}}
                                        </td>
                                        <td>

                                            {{$transaction->representative->renewal_price+$transaction->representative->transfer_price}}
                                        </td>
                                    @endif

{{--                                    @if($key == "0" )--}}
                                        <td >{{$sum[$key]}}</td>
{{--                                    @endif--}}
{{--                                    @if ($loop->parent->first && $key == "0" )--}}
{{--                                        <td rowspan="{{$transactions_count}}">{{$total_all}}</td>--}}
{{--                                    @endif--}}


                                </tr>
                                @if($loop->last)
                                    <tr style="font-weight: bold">
                                        <td colspan="5">{{translate('Total2')}}</td>
                                        <td colspan="2" class="btn-soft-success aiz-side-nav-text">{{$total_all}} <span class="small">ريال</span> </td>

                                    </tr>
                                @endif
{{--                            @endif--}}
                        @endforeach
{{--                        @endforeach--}}

                    </tbody>
                </table>
                <div class="aiz-pagination mt-4">
                    <h6 class="mb-md-0 h6">{{translate("Ownership Count")}}:<span class="btn btn-icon btn-circle  btn-soft-danger"> {{$count_owner}}</span></h6>
                    <h6 class="mb-md-0 h6">{{translate("Renewal Count")}}:<span class="btn btn-icon btn-circle  btn-soft-danger"> {{$count_renewal}}</span></h6>

                </div>
                </div>
                <button class="btn btn-md btn-success "  onClick="printDiv();" id="print_id">   <span  class=" las la-print"> {{ translate('Print') }}</span> </button>

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
