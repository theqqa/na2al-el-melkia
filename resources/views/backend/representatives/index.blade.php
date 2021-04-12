@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="row align-items-center">
		<div class="col-md-6">
			<h1 class="h3">{{translate('All Representatives')}}</h1>
		</div>
		<div class="col-md-6 text-md-right">
			<a href="{{ route('representatives.create') }}" class="btn btn-circle btn-info">
				<span>{{translate('Add New representative')}}</span>
			</a>
		</div>
	</div>
</div>

<div class="card">
    <form class="" id="sort_sellers" action="" method="GET">
      <div class="card-header row gutters-5">
        <div class="col text-center text-md-left">
          <h5 class="mb-md-0 h6">{{ translate('Representatives') }}</h5>
        </div>

          <div class="col-md-3 ml-auto">
            <select class="form-control aiz-selectpicker" name="active_status" id="active_status" onchange="sort_sellers()">
                <option value="">{{translate('Filter by Active')}}</option>
                <option value="1"  @isset($active_status) @if($active_status == '1') selected @endif @endisset>{{translate('ACTIVE')}}</option>
                <option value="0"  @isset($active_status) @if($active_status == '0') selected @endif @endisset>{{translate('Non-ACTIVE')}}</option>
            </select>
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
                <th data-breakpoints="lg">#</th>
                <th>{{translate('Name')}}</th>
                <th>{{translate('Code')}}</th>

                <th data-breakpoints="lg">{{translate('Transfer Price')}}</th>
                <th data-breakpoints="lg">{{translate('Renewal Price')}}</th>
                <th data-breakpoints="lg">{{translate('Initial Balance')}}</th>
                <th data-breakpoints="lg">{{translate('Deserved Amount')}}</th>
                <th data-breakpoints="lg">{{translate('Approval')}}</th>>
                <th width="10%">{{translate('Options')}}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($representatives as $key => $val)
                    <tr>
                        <td>{{ ($key+1) + ($representatives->currentPage() - 1)*$representatives->perPage() }}</td>
                        <td>{{$val->name}}</td>
                        <td>{{$val->code}}</td>

                        <td>{{single_price($val->transfer_price)}}</td>
                        <td>{{single_price($val->renewal_price)}}</td>
                        <td>{{single_price($val->initial_balance)}}</td>
                        <td>{{single_price($val->deserved_amount)}}
                        </td>
                        <td>
                            <label class="aiz-switch aiz-switch-success mb-0">
                                <input onchange="update_approved(this)" value="{{ $val->id }}" type="checkbox" <?php if($val->active == 1) echo "checked";?> >
                                <span class="slider round"></span>
                            </label>
                        </td>
                        <td class="text-right">
                            <a class="btn btn-soft-success btn-icon btn-circle btn-sm" href="{{route('representatives.show', encrypt($val->id))}}" title="{{ translate('show') }}">
                                <i class="las la-eye"></i>
                            </a>
                            <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{route('representatives.edit', encrypt($val->id))}}" title="{{ translate('Edit') }}">
                                <i class="las la-edit"></i>
                            </a>
                            <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{route('representatives.destroy', $val->id)}}" title="{{ translate('Delete') }}">
                                <i class="las la-trash"></i>
                            </a>
                        </td>

                    </tr>

            @endforeach
            </tbody>
        </table>
        <div class="aiz-pagination">
          {{ $representatives->appends(request()->input())->links() }}
        </div>
    </div>
</div>

@endsection

@section('modal')
	<!-- Delete Modal -->
	@include('modals.delete_modal')



@endsection

@section('script')
    <script type="text/javascript">

        function update_approved(el){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('{{ route('representatives.active') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                if(data == 1){
                    AIZ.plugins.notify('success', '{{ translate('Active representative updated successfully') }}');
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
