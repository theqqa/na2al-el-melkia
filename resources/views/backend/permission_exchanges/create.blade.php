@extends('backend.layouts.app')

@section('content')

<div class="row">
    <div class="col-lg-10 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{translate('Permission Exchange Information')}}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('permission_exchanges.store') }}" method="POST">
                    @csrf
                    <div class="form-group row">
                        <label class="col-sm-3 control-label" for="name">{{translate('Price')}}</label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="{{translate('Price')}}" id="price" name="price" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 control-label" for="name">{{translate('Date')}}</label>
                        <div class="col-sm-9">
                            <input type="date" placeholder="{{translate('Date')}}" id="date" name="date" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 control-label" for="background_color">{{translate('Expense')}} </label>
                        <div class="col-sm-9">
                            <select class="form-control aiz-selectpicker" name="expense_id" id="expense_id" data-live-search="true" required>
                                @foreach ($expense_lists as $val)
                                    <option value="{{ $val->id }}">{{ $val->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 control-label" for="name">{{translate('Expense By')}}</label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="{{translate('Expense By')}}" id="expense_by" name="expense_by" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-3 col-from-label">{{translate('Description')}}</label>
                        <div class="col-lg-9">
                            <textarea name="description" rows="5" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-primary">{{translate('Save')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
    <script type="text/javascript">
        $(document).ready(function(){

        });
    </script>
@endsection
