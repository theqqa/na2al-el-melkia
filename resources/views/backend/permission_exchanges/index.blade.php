@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="row align-items-center">
		<div class="col-md-6">
			<h1 class="h3">{{translate('All Permission Exchanges')}}</h1>
		</div>
        @if(Auth::user()->user_type != 'admin')

        <div class="col-md-6 text-md-right">
			<a href="{{ route('permission_exchanges.create') }}" class="btn btn-circle btn-info">
				<span>{{translate('Create New Permission Exchange')}}</span>
			</a>
		</div>
        @endif
	</div>
</div>

<div class="card">

            <form class="" id="sort_sellers" action="" method="GET">

                <div class="card-header row gutters-5">

                    <div class="col text-center text-md-left">
                        <h5 class="mb-md-0 h6">{{ translate('Permission Exchanges') }}</h5>
                    </div>

                    <div class="col-md-3 ml-auto">
                        <select class="from-control aiz-selectpicker"onchange="sort_sellers()" name="expense_id" required>
                            <option value="null" @if(empty($expense_id ) )selected @endif>{{ translate('All') }}</option>
                            @foreach($expense as $key=>$val)
                                <option value="{{$val->id}}" @if($expense_id == $val->id) selected @endif>{{ $val->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mb-0">
                            <input type="text" class="form-control form-control-sm aiz-date-range" onchange="sort_sellers()"  name="date_range"@isset($date_range) value="{{ $date_range }}" @endisset placeholder="{{ translate('Type Date Range') }}">

                        </div>
                    </div>
                </div>
            </form>



    <div class="card-body">
        <table class="table aiz-table mb-0" >
            <thead>
                <tr>
                    <th data-breakpoints="lg">#</th>
                    <th>{{translate('Date')}}</th>
                    <th data-breakpoints="lg">{{ translate('Price') }}</th>
                    <th data-breakpoints="lg">{{ translate('Expense') }}</th>
                    <th data-breakpoints="lg">{{ translate('Expense by') }}</th>

                    <th data-breakpoints="lg">{{ translate('Approved') }}</th>
                    <th data-breakpoints="lg">{{ translate('Confirm Exchange') }}</th>


                    <th class="text-right">{{translate('Options')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($permission_exchanges as $key => $permission_exchange)
                    <tr>
                        <td>{{ ($key+1) + ($permission_exchanges->currentPage() - 1)*$permission_exchanges->perPage() }}</td>
                        <td>{{ date('Y-m-d', strtotime($permission_exchange->date)) }}</td>
                        <td>{{ $permission_exchange->price}}</td>

                        <td>{{ $permission_exchange->expense->name }}</td>


                        <td>{{ $permission_exchange->expense_by==1?translate('Cache'):translate('Bank transfer')}}</td>
                        <td>
                            <label class="aiz-switch aiz-switch-success mb-0">
                                <input @if(auth()->user()->user_type == 'admin' && $permission_exchange->status == 0) onchange="update_approved(this)" @else disabled  @endif value="{{ $permission_exchange->id }}" type="checkbox" <?php if($permission_exchange->approved == 1) echo "checked";?> >
                                <span class="slider round"></span>
                            </label>
                        </td>
                        <td>
                        @if($permission_exchange->status == 1)
                         <span class="btn-soft-success">  {{translate('Exchange Completed')}} </span>
                            @else
                                <span class="btn-soft-danger">   {{translate('Not Exchange Completed')}} </span>

                            @endif
                        </td>
                        <td class="text-right">
                            <a class="btn btn-soft-info btn-icon btn-circle btn-sm"  href="{{route('permission_exchanges.show',$permission_exchange->id )}}" title="{{ translate('Show') }}">
                                <i class="las la-eye"></i>
                            </a>
                        @if($permission_exchange->approved == 0)

                            <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" @if($permission_exchange->approved == 0) href="{{route('permission_exchanges.edit',$permission_exchange->id )}}" @else disabled  @endif title="{{ translate('Edit') }}">
                                <i class="las la-edit"></i>
                            </a>
                            <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" @if($permission_exchange->approved == 0) data-href="{{route('permission_exchanges.destroy', $permission_exchange->id)}}" @else disabled="" @endif  title="{{ translate('Delete') }}">
                                <i class="las la-trash"></i>
                            </a>

                        @else
                                @if($permission_exchange->status == 0 && auth()->user()->user_type == 'staff' )
                                <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" @if($permission_exchange->status == 0) onclick="confirm_permission({{ $permission_exchange->id }});" @else disabled @endif  title="{{ translate('The possibility of exchange') }}">
                                    <i class="las la-check-double"></i>
                                </a>
                                @endif
{{--                                <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" @if($permission_exchange->approved == 1) data-href="{{route('permission_exchanges.destroy', $permission_exchange->id)}}" @else disabled="" @endif  title="{{ translate('Delete') }}">--}}
{{--                                    <i class="las la-print"></i>--}}
{{--                                </a>--}}
                                    <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{ route('permission.download', $permission_exchange->id) }}" title="{{ translate('Download Permission D') }}">
                                        <i class="las la-download"></i>
                                    </a>

    @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="clearfix">
            <div class="pull-right">
                {{ $permission_exchanges->appends(request()->input())->links() }}
            </div>
        </div>
    </div>
</div>

@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection

@section('script')
    <script type="text/javascript">
        function confirm_permission(el){


            $.post('{{ route('permission_exchanges.confirm') }}', {_token:'{{ csrf_token() }}', id:el}, function(data){
                if(data == 1){

                    AIZ.plugins.notify('success', '{{ translate('Permission Exchanges updated successfully') }}');
                    location.reload()

                }
                else{
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }
        function update_approved(el){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('{{ route('permission_exchanges.approved') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                if(data == 1){

                    AIZ.plugins.notify('success', '{{ translate('Permission Exchanges updated successfully') }}');
                    location.reload()

                }
                else{
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }
        function sort_sellers(el){
            $('#sort_sellers').submit();
        }

    </script>
@endsection
