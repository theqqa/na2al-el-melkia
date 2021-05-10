@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <h5 class="mb-0 h6">{{translate('Edit SubRepresentative Information')}}</h5>
</div>

<div class="col-lg-6 mx-auto">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{translate('SubRepresentative Information')}}</h5>
        </div>

        <div class="card-body">
          <form action="{{ route('sub_representatives.update', $representative->id) }}" method="POST">
                <input name="_method" type="hidden" value="PATCH">
                @csrf
              <div class="form-group row">
                  <label class="col-sm-3 col-from-label" for="name">{{translate('Name')}}</label>
                  <div class="col-sm-9">
                      <input type="text" placeholder="{{translate('Name')}}" id="name" name="name" value="{{$representative->name}}" class="form-control  {{ $errors->has('name') ? ' is-invalid' : '' }}" required>
                      @if ($errors->has('name'))
                          <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                      @endif
                  </div>
              </div>

                <div class="form-group mb-0 text-right">
                    <button type="submit" class="btn btn-primary">{{translate('Save')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
