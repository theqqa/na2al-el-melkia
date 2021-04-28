@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <h5 class="mb-0 h6">{{translate('Expense Item Information')}}</h5>
</div>

<div class="col-lg-8 mx-auto">
    <div class="card">
        <div class="card-body p-0">

            <form class="p-4" action="{{ route('expenses.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="name">{{translate('Name')}} <i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{translate('Name')}}" id="name" name="name"  class="form-control" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="email">{{translate('Initial Balance')}}</label>
                    <div class="col-sm-9">
                        <input type="number" placeholder="{{translate('Initial Balance')}}" id="initial_balance" name="initial_balance" class="form-control" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="email">{{translate('Register At')}}</label>
                    <div class="col-sm-9">
                        <input type="date" placeholder="{{translate('Register At')}}" id="register_at" name="register_at" class="form-control" required>
                    </div>
                </div>

                <div class="form-group mb-0 text-right">
                    <button type="submit"  onclick="this.disabled = true" class="btn btn-primary">{{translate('Save')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
