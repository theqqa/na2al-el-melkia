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

                <table class="table table-bordered  mb-0">
                    <thead>
                        <tr>
                            <th>{{ translate('User Name') }}</th>
                            <th data-breakpoints="lg">{{ translate('Representative Name') }}</th>
                            <th>{{translate('Transactions Id')}}</th>
                            <th data-breakpoints="lg">{{ translate('Type') }}</th>
                            <th data-breakpoints="lg">{{ translate('Price') }}</th>

                            <th data-breakpoints="lg">{{ translate('Time Date') }}</th>
                            <th data-breakpoints="lg">{{ translate('Total') }}</th>
                            <th data-breakpoints="lg">{{ translate('Total Income') }}</th>
                    </thead>
                    <tbody>
                        @foreach ($transactions as $key_1 => $val)
                            <?php $ff=$val->count();

                            $total=0;?>

                            @foreach ($val as $key => $transaction)

                                {{--                            @if($transaction->user != null)--}}
                                <tr>

                                    <td >{{ $transaction->user->name }}</td>
                                    @if($key == "0")
                                    <td rowspan="{{$ff}}" >{{$transaction->representative->name}}</td>
                                    @endif
                                    <td>{{ $transaction->transaction_id}}</td>

                                    @if ($transaction->type==1)
                                    <td>
                                        {{__("Ownership")}}
                                    </td>
                                        <td>
                                            <?php $total+=$transaction->representative->transfer_price ;?>
                                            {{$transaction->representative->transfer_price}}
                                        </td>
                                    @elseif($transaction->type==2)
                                     <td>
                                            {{__("Renewal")}}
                                    </td>
                                        <td>
                                            <?php $total+=$transaction->representative->renewal_price ;?>
                                            {{$transaction->representative->renewal_price}}
                                        </td>
                                    @elseif($transaction->type==3)
                                        <td>
                                            {{__("Renewal")}}
                                        </td>
                                        <td>
                                            <?php $total+=$transaction->representative->renewal_price+$transaction->representative->transfer_price ;?>

                                            {{$transaction->representative->renewal_price+$transaction->representative->transfer_price}}
                                        </td>
                                    @endif

                                    <td >{{ date('Y-m-d H:i:s', strtotime($transaction->timedate)) }}</td>
                                    @if($key == "0" )
                                        <td rowspan="{{$ff}}">{{$total_1[$key_1]}}</td>
                                    @endif
                                    @if ($loop->parent->first && $key == "0" )
                                        <td rowspan="{{$transactions_count}}">{{$total_all}}</td>
                                    @endif


                                </tr>
{{--                            @endif--}}
                        @endforeach
                        @endforeach

                    </tbody>
                </table>
{{--                <div class="aiz-pagination mt-4">--}}
{{--                    {{ $transactions->links() }}--}}
{{--                </div>--}}
            </div>
        </div>
    </div>
</div>

@endsection
