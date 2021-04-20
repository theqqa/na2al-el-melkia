@extends('backend.layouts.app')

@section('content')

<div class="row">
    <div class="col-lg-10 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{translate('transaction Information')}}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('transactions.store') }}" method="POST">
                    @csrf
                    <div class="form-group row">
                        <label class="col-lg-3 control-label" for="name">{{translate('Transaction Id')}}</label>
                        <div class="col-lg-9">
                            <input type="text" placeholder="{{translate('Transaction Id')}}" id="name" name="transaction_id" class="form-control {{ $errors->has('transaction_id') ? ' is-invalid' : '' }}" required>
                            @if ($errors->has('transaction_id'))
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('transaction_id') }}</strong>
                                    </span>
                            @endif
                        </div>

                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 control-label" for="background_color">{{translate('Representative')}} </label>
                        <div class="col-lg-9">
                            <select class="form-control aiz-selectpicker" name="representative_id" id="representative_id" data-live-search="true" required>
                                @foreach ($rep_lists as $val)
                                    <option value="{{ $val->id }}">{{ $val->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 control-label" for="name">{{translate('Type')}}</label>
                        <div class="col-lg-9">
                            <select name="type" id="type" class="form-control aiz-selectpicker" required>
                                <option value="1">{{translate('Ownership')}}</option>
                                <option value="2">{{translate('Renewal')}}</option>
                                <option value="3">{{translate('Both')}}</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-3 col-from-label" for="email">{{translate('Register At')}}</label>
                        <div class="col-lg-9">
                            <input type="datetime-local" placeholder="{{translate('Register At')}}" id="register_at" name="register_at" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-3 col-from-label">{{translate('Notes')}}</label>
                        <div class="col-lg-9">
                            <textarea name="notes" rows="5" class="form-control"></textarea>
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
