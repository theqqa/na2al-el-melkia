@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="row align-items-center">
		<div class="col-md-6">
			<h1 class="h3">{{translate('All Transactions')}}</h1>
		</div>
		<div class="col-md-6 text-md-right">

        @if(Auth::user()->user_type != 'admin' )

{{--            <a href="{{ route('transaction.uploaded_file') }}" class="btn btn-circle btn-danger ">--}}
{{--				<span>{{translate('Upload files for Transaction')}}</span>--}}
{{--            </a>--}}
                <a href="{{ route('transactions.create') }}" class="btn btn-circle  btn-info">
                    <span>{{translate('Create New Transaction')}}</span>
                </a>
            @endif
		</div>
	</div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0 h6">{{translate('Transactions')}}</h5>
        <div class="pull-right clearfix">
            <form action="{{ route('transaction.uploaded_file_index') }}" method="GET">




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


                    <div class="col-md-2">
                        <button class="btn btn-md btn-primary" type="submit">
                            {{ translate('Filter') }}
                        </button>
                    </div>
                </div>
            </form>


        </div>
    </div>
    <div class="card-body">
        <table class="table aiz-table mb-0" >
            <thead>
                <tr>
                    <th data-breakpoints="lg">#</th>
                    <th>{{translate('Transactions Id')}}</th>
                    <th>{{ translate('User Name') }}</th>

                    <th data-breakpoints="lg">{{ translate('Representative Name') }}</th>
                    <th data-breakpoints="lg">{{ translate('Sub Representative') }}</th>


                    <th data-breakpoints="lg">{{ translate('Type') }}</th>
                    <th data-breakpoints="lg">{{ translate('Time Date') }}</th>
                    <th data-breakpoints="lg">{{ translate('Status') }}</th>
                    <th class="text-right">{{translate('Options')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactions as $key => $transaction)
                    <tr>
                        <td>{{ ($key+1) + ($transactions->currentPage() - 1)*$transactions->perPage() }}</td>
                        <td>{{ $transaction->transaction_id}}</td>
                        <td >{{ $transaction->user->name }}</td>

                        <td>{{\App\Models\Representative::find($transaction->representative_id)->name}}</td>

                        <td>{{ $transaction->subRepresentative->name}}</td>

                        <td>
                            @if ($transaction->type==1)
                                {{translate("Ownership")}}
                            @elseif($transaction->type==2)
                                {{translate("Renewal")}}
                            @elseif($transaction->type==3)
                                {{translate("Both")}}
                            @endif
                        </td>
                        <td>{{ date('Y-m-d H:i:s', strtotime($transaction->timedate)) }}</td>
                        @if(!empty($transaction->files))
                            <?php
                            $file_tran=explode(',',$transaction->files);
                            ?>
                            <td>
                                <a class="btn btn-soft-success  btn-sm" href="{{route('transaction.download_file',['train_id'=>$transaction])}}"  title="{{ translate('Download') }}">
                                    {{  translate('Download')}}
                                </a>
                            </td>
                        @else
                            <td>
                                <span class="btn-danger">{{     translate('No file uploaded')}}</span>
                            </td>
@endif
						<td class="text-right">

                            <a class="btn btn-soft-warning btn-icon btn-circle btn-sm" href="{{route('transaction.uploaded_file', ['transaction_id'=>$transaction->id] )}}" title="{{ translate('Upload File') }}">
                                <i class="las la-upload"></i>
                            </a>


                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="clearfix">
            <div class="pull-right">
                {{ $transactions->appends(request()->input())->links() }}
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

        function sort_sellers(el){
            $('#sort_sellers').submit();
        }
        function update_flash_deal_status(el){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('{{ route('flash_deals.update_status') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                if(data == 1){
                    location.reload();
                }
                else{
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }
        function update_flash_deal_feature(el){
            if(el.checked){
                var featured = 1;
            }
            else{
                var featured = 0;
            }
            $.post('{{ route('flash_deals.update_featured') }}', {_token:'{{ csrf_token() }}', id:el.value, featured:featured}, function(data){
                if(data == 1){
                    location.reload();
                }
                else{
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }
    </script>
@endsection
