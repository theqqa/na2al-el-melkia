@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="row align-items-center">
		<div class="col-md-6">
			<h1 class="h3">{{translate('All Catch Receipts')}}</h1>
		</div>
        @if(Auth::user()->user_type != 'admin')
		<div class="col-md-6 text-md-right">
			<a href="{{ route('catch_receipts.create') }}" class="btn btn-circle btn-info">
				<span>{{translate('Create New Catch Receipt')}}</span>
			</a>
		</div>
            @endif
	</div>
</div>

<div class="card">
    <form class="" id="sort_sellers" action="" method="GET">

    <div class="card-header row gutters-5">

        <div class="col text-center text-md-left">
            <h5 class="mb-md-0 h6">{{ translate('Catch Receipts') }}</h5>
        </div>

        <div class="col-md-3 ml-auto">
            <input type="text" class="form-control" id="search" onchange="sort_sellers()" name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset placeholder="{{ translate('Type Representative Name & Enter') }}">

        </div>
        <div class="col-md-3">
            <div class="form-group mb-0">
                <input type="text" class="form-control form-control-sm aiz-date-range" onchange="sort_sellers()" id="search" name="date_range"@isset($date_range) value="{{ $date_range }}" @endisset placeholder="{{ translate('Type Date Range') }}">

            </div>
        </div>
    </div>
    </form>

    <div class="card-body">
        <table class="table aiz-table mb-0" >
            <thead>
                <tr>
                    <th data-breakpoints="lg">#</th>
                    <th>{{translate('Code')}}</th>
                    <th>{{translate('Date')}}</th>
                    <th data-breakpoints="lg">{{ translate('Representative Name') }}</th>
                    <th data-breakpoints="lg">{{ translate('Price') }}</th>
                    <th data-breakpoints="lg">{{ translate('Payment by') }}</th>

                    <th class="text-right">{{translate('Options')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($catch_receipts as $key => $catch_receipt)
                    <tr>
                        <td>{{ ($key+1) + ($catch_receipts->currentPage() - 1)*$catch_receipts->perPage() }}</td>
                        <td>{{ $catch_receipt->code}}</td>

                        <td>{{ date('Y-m-d', strtotime($catch_receipt->date)) }}</td>
                        <td>{{ $catch_receipt->representative->name }}</td>
                        <td>{{ $catch_receipt->price}}</td>
                        <td>{{ ($catch_receipt->payment_by==1)?translate('Cache') :translate('Bank transfer') }}</td>

						<td class="text-right">

<a class="btn btn-soft-info btn-icon btn-circle btn-sm" href="{{route('catch_receipts.edit', ['lang'=>env('DEFAULT_LANGUAGE'),$catch_receipt->id] )}}" title="{{ translate('Edit') }}">
   <i class="las la-edit"></i>
</a>
<a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{route('catch_receipts.show',$catch_receipt->id )}}" title="{{ translate('show') }}">
   <i class="las la-eye"></i>
</a>
<a class="btn btn-soft-success btn-icon btn-circle btn-sm" href="{{ route('catch_receipt.download', $catch_receipt->id) }}" title="{{ translate('Download Catch Receipt') }}">
   <i class="las la-download"></i>
</a>
                            <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{route('catch_receipt.destroy', $catch_receipt->id)}}" title="{{ translate('Delete') }}">
                                <i class="las la-trash"></i>
                            </a>
</td>
</tr>
@endforeach
</tbody>
</table>
<div class="clearfix">
<div class="pull-right">
{{ $catch_receipts->appends(request()->input())->links() }}
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
function update_approved(el){
if(el.checked){
var status = 1;
}
else{
var status = 0;
}
$.post('{{ route('catch_receipt.approved') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
if(data == 1){

AIZ.plugins.notify('success', '{{ translate('catch receipt updated successfully') }}');
location.reload()

}
else{
AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
}
});
}

</script>
@endsection
