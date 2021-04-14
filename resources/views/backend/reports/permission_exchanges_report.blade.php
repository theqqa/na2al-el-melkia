@extends('backend.layouts.app')

@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class=" align-items-center">
       <h1 class="h3">{{translate('Permission Expense Report')}}</h1>
	</div>
</div>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('permission_exchanges_report.index') }}" method="GET">




                    <div class="card-header row gutters-5">
                        <div class="col text-center text-md-left">
                            <h5 class="mb-md-0 h6">{{ translate('Sort by Expense Item') }}</h5>
                        </div>

                            <div class="col-md-3 ml-auto">
                                <select class="from-control aiz-selectpicker" name="expense_id" required>
                                    <option value="null" @if(empty($expense_id ) )selected @endif>{{ translate('All') }}</option>
                                    @foreach($expense as $key=>$val)
                                        <option value="{{$val->id}}" @if($expense_id == $val->id) selected @endif>{{ $val->name }}</option>
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
<table class="table table-bordered  mb-0" >
    <thead>
    <tr>
        <th data-breakpoints="lg">#</th>
        <th>{{translate('Date')}}</th>
        <th data-breakpoints="lg">{{ translate('Price') }}</th>
        <th data-breakpoints="lg">{{ translate('Expense') }}</th>
        <th data-breakpoints="lg">{{ translate('Expense by') }}</th>

        <th data-breakpoints="lg">{{ translate('Approved') }}</th>
        <th data-breakpoints="lg">{{ translate('Confirm Exchange') }}</th>
        <th data-breakpoints="lg">{{ translate('Nots') }}</th>

    </tr>
    </thead>
    <tbody>
    @foreach($permission_exchanges as $key => $permission_exchange)
        <tr>
            <td>{{ $key+1}}</td>
            <td>{{ date('Y-m-d', strtotime($permission_exchange->date)) }}</td>
            <td>{{ $permission_exchange->price}}</td>

            <td>{{ $permission_exchange->expense->name }}</td>

            <td>{{ $permission_exchange->expense_by==1?translate('Cache'):translate('Bank transfer')}}</td>

            <td>
                @if($permission_exchange->approved == 1)
                    <span class="btn-soft-success">  {{translate('Admin Approved')}} </span>
                @else
                    <span class="btn-soft-danger">   {{translate('Admin Not Approved')}} </span>

                @endif
            </td>
            <td>
                @if($permission_exchange->status == 1)
                    <span class="btn-soft-success">  {{translate('Exchange Completed')}} </span>
                @else
                    <span class="btn-soft-danger">   {{translate('Not Exchange Completed')}} </span>

                @endif
            </td>
<td>{{$permission_exchange->description}} </td>
        </tr>
        @if($loop->last)
            @if(!empty($expense_id ) )
            <?php $expense_selected=App\Models\Expense::find($expense_id)?>
            <tr style="font-weight: bold">
                <td colspan="2">{{translate('Initial Balance')}}</td>
                <td colspan="6" class="btn-soft-success aiz-side-nav-text">{{$expense_selected->initial_balance}} <span class="small">ريال</span> </td>

            </tr>
            <tr style="font-weight: bold">
                <td colspan="2">{{translate('Total2')}}</td>
                <td colspan="6" class="btn-soft-success aiz-side-nav-text">{{$total_price+$expense_selected->initial_balance }} <span class="small">ريال</span> </td>

            </tr>
                @else
                <tr style="font-weight: bold">
                    <td colspan="2">{{translate('Total2')}}</td>
                    <td colspan="6" class="btn-soft-success aiz-side-nav-text">{{$total_price }} <span class="small">ريال</span> </td>

                </tr
        @endif
        @endif
    @endforeach
    </tbody>
</table>
</div>
                <button class="btn btn-md btn-success "  onClick="printDiv();" id="print_id">   <span  class=" las la-print"> {{ translate('Print') }}</span> </button>

                {{--                <div class="aiz-pagination mt-4">--}}
{{--                    {{ $permission_exchanges->links() }}--}}
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
