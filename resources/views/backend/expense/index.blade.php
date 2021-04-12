@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
{{--	<div class="align-items-center">--}}
{{--			<h1 class="h3">{{translate('All Expense Items')}}</h1>--}}
{{--	</div>--}}
</div>





<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{translate('All Expense Items')}}</h1>
        </div>
        <div class="col-md-6 text-md-right">
            <a href="{{ route('expenses.create') }}" class="btn btn-circle btn-info">
                <span>{{translate('Create New Expense')}}</span>
            </a>
        </div>
    </div>
</div>





<div class="card">

    <form class="" id="sort_sellers" action="" method="GET">

        <div class="card-header row gutters-5">

            <div class="col text-center text-md-left">
                <h5 class="mb-md-0 h6">{{translate('All Expense Items')}}</h5>
            </div>

            <div class="col-md-3 ml-auto">

            </div>
            <div class="col-md-3">
                <div class="form-group mb-0">
                    <input type="text" class="form-control" id="search" name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset placeholder="{{ translate('Type name & Enter') }}">

                </div>
            </div>
        </div>
    </form>



    <div class="card-body">
        <table class="table aiz-table mb-0">
            <thead>
            <tr>
                <th>#</th>
                <th>{{translate('Name')}}</th>
                <th>{{translate('Initial Balance')}}</th>
                <th>{{translate('Total cost')}}</th>
                <th class="text-right">{{translate('Options')}}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($expenses as $key => $val)
                <tr>

                    <td>{{ ($key+1) + ($expenses->currentPage() - 1)*$expenses->perPage() }}</td>
                    <td>{{ $val->getTranslation('name') }}</td>
                    <td>{{ $val->initial_balance }}</td>

                    <td>{{ \App\Models\PermissionExchange::whereExpenseId($val->id)->whereStatus(1)->sum('price') + $val->initial_balance }}</td>

                    <td class="text-right">
                        <a class="btn btn-soft-info btn-icon btn-circle btn-sm" href="{{route('expenses.show', [$val->id] )}}" title="{{ translate('Edit') }}">
                            <i class="las la-eye"></i>
                        </a>
                        <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{route('expenses.edit', ['lang'=>env('DEFAULT_LANGUAGE'),$val->id] )}}" title="{{ translate('Edit') }}">
                            <i class="las la-edit"></i>
                        </a>
                        <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{route('expenses.destroy', $val->id)}}" title="{{ translate('Delete') }}">
                            <i class="las la-trash"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="aiz-pagination">
            {{ $expenses->appends(request()->input())->links() }}
        </div>
    </div>
</div>

@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection

@section('script')
<script type="text/javascript">
    function sort_brands(el){
        $('#sort_brands').submit();
    }
</script>
@endsection
