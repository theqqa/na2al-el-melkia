@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <h5 class="mb-0 h6">{{translate('Show Representative Information')}}</h5>
</div>

<div class="col-lg-6 mx-auto">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{translate('Representative Information')}}</h5>
        </div>
<?php
        $transactions=\App\Models\Transaction::where('representative_id',$representative->id);
        $ownership=$transactions->orWhere(function($query) {
                $query->  where('type',1)
                     ->Where('type', 3);
            })->count();
        $renewal=$transactions->orWhere(function($query) {
            $query->  where('type',2)
                ->Where('type', 3);
            })->count();
        ?>
        <div class="card-body">
{{--          <form action="{{ route('representatives.update', $representative->id) }}" method="POST">--}}
{{--                <input name="_method" type="hidden" value="PATCH">--}}
{{--                @csrf--}}
              <div class="form-group row">
                  <label class="col-sm-3 col-from-label" for="name">{{translate('Name')}}</label>
                  <div class="col-sm-9">
                      <input type="text" placeholder="{{translate('Name')}}" id="name" name="name" value="{{$representative->name}}" class="form-control" required>
                  </div>
              </div>
            <div class="form-group row">
                <label class="col-sm-3 col-from-label" for="email">{{translate('Email')}}</label>
                <div class="col-sm-9">
                    <input type="email" placeholder="{{translate('Email')}}" id="email" name="email" value="{{$representative->email}}"  class="form-control" required>
                </div>
            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-from-label" for="code">{{translate('Code')}}</label>
                                <div class="col-sm-9">
            <input type="text"  id="code" name="code" class="form-control"  value="{{ $representative->code }}" readonly>
                                </div>
                            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-from-label" for="register_at">{{translate('Register At')}}</label>
                <div class="col-sm-9">
                    <input type="date" placeholder="{{translate('Register At')}}" id="register_at" name="register_at" value="{{ date('Y-m-d',strtotime($representative->register_at)) }}"  class="form-control" required>
                </div>
            </div>
              <div class="form-group row">
                  <label class="col-sm-3 col-from-label" for="transfer_price">{{translate('Transfer Price')}}</label>
                  <div class="col-sm-9">
                      <input type="number" placeholder="{{translate('Transfer Price')}}" id="transfer_price" value="{{$representative->transfer_price}}"  name="transfer_price" class="form-control" required>
                  </div>
              </div>
              <div class="form-group row">
                  <label class="col-sm-3 col-from-label" for="renewal_price">{{translate('Renewal Price')}}</label>
                  <div class="col-sm-9">
                      <input type="number" placeholder="{{translate('Renewal Price')}}" id="renewal_price" value="{{$representative->renewal_price}}"  name="renewal_price" class="form-control" required>
                  </div>
              </div>
              <div class="form-group row">
                  <label class="col-sm-3 col-from-label" for="deserved_amount">{{translate('Deserved Amount')}}</label>
                  <div class="col-sm-9">
                      <input type="number" placeholder="{{translate('Deserved Amount')}}" id="deserved_amount" value="{{$representative->deserved_amount}}"  name="deserved_amount" class="form-control" required>
                  </div>
              </div>
            <div class="form-group row">
                <label class="col-sm-3 col-from-label" for="initial_balance">{{translate('Initial Balance')}}</label>
                <div class="col-sm-9">
                    <input type="number" placeholder="{{translate('Initial Balance')}}" id="initial_balance"  value="{{$representative->initial_balance}}" name="initial_balance" class="form-control" required>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-from-label" for="initial_balance">{{translate('Number of renewal service')}}</label>
                <div class="col-sm-9">
                    <input type="number" placeholder="{{translate('Number of renewal service')}}" id="initial_balance"  value="{{$renewal}}" name="initial_balance" class="form-control" required>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-from-label" for="initial_balance">{{translate('Number of Ownership service')}}</label>
                <div class="col-sm-9">
                    <input type="number" placeholder="{{translate('Number of Ownership service')}}" id="initial_balance"  value="{{$ownership}}" name="initial_balance" class="form-control" required>
                </div>
            </div>

                <div class="form-group mb-0 text-right">
                    <a href="{{route("representatives.index")}}" class="btn btn-primary">{{translate('back')}}</a>
                </div>
{{--            </form>--}}
        </div>
    </div>
</div>

@endsection
