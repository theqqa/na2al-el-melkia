@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <h5 class="mb-0 h6">{{translate('Catch Receipts Information')}}</h5>
</div>

<div class="row">
    <div class="col-lg-10 mx-auto">
        <div class="card">
            <div class="card-body p-0">

              <form class="p-4" action="{{ route('catch_receipts.update', $catch_receipt->id) }}" method="POST">
                @csrf
                  <input type="hidden" name="_method" value="PATCH">


                  <div class="form-group row">
                      <label class="col-sm-3 control-label" for="name">{{translate('Price')}}</label>
                      <div class="col-sm-9">
                          <input type="text" placeholder="{{translate('Price')}}" id="price" value="{{$catch_receipt->price}}" name="price" class="form-control" required>
                      </div>
                  </div>
                  <div class="form-group row">
                      <label class="col-sm-3 control-label" for="name">{{translate('Code')}}</label>
                      <div class="col-sm-9">
                          <input type="text" placeholder="{{translate('Code')}}" id="code" value="{{$catch_receipt->code}}" name="code" class="form-control" readonly required>
                      </div>
                  </div>
                  <div class="form-group row">
                      <label class="col-sm-3 control-label" for="name">{{translate('Date')}}</label>
                      <div class="col-sm-9">
                          <input type="date" placeholder="{{translate('Date')}}" id="date" value="{{ date('Y-m-d',strtotime($catch_receipt->date)) }}" name="date" class="form-control" required>
                      </div>
                  </div>
                  <input type="hidden" name="representative_id" value="{{$catch_receipt->representative_id}}">
                  <div class="form-group row">
                      <label class="col-sm-3 control-label" for="background_color">{{translate('Representative')}} </label>
                      <div class="col-sm-9">
                          <select class="form-control aiz-selectpicker" name="representative_id" id="representative_id" data-live-search="true" disabled  required readonly>
                              @foreach ($rep_lists as $val)
                                  <option value="{{ $val->id }}" @if($catch_receipt->representative_id == $val->id  ) selected @endif>{{ $val->name }}</option>
                              @endforeach
                          </select>
                      </div>
                  </div>
                  <div class="form-group row">
                      <label class="col-sm-3 control-label" for="name">{{translate('Payment By')}}</label>
                      <div class="col-sm-9">
                          <select class="form-control aiz-selectpicker" name="payment_by" id="payment_by" data-live-search="true" required>
                              <option value="1" @if($catch_receipt->payment_by ==1 ) selected @endif>{{ translate('cache')}}</option>
                              <option value="2"  @if($catch_receipt->payment_by ==2 ) selected @endif>{{ translate('Bank transfer')}}</option>
                          </select>
                      </div>
                  </div>

                  <div class="form-group row">
                      <label class="col-lg-3 col-from-label">{{translate('Description')}}</label>
                      <div class="col-lg-9">
                          <textarea name="description" rows="5" class="form-control">{{$catch_receipt->description}}</textarea>
                      </div>
                  </div>

                  <div class="form-group mb-0 text-right">
                      <button type="submit" onclick="this.disabled = true" class="btn btn-primary">{{translate('Save')}}</button>
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
