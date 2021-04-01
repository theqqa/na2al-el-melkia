@extends('backend.layouts.app')

@section('content')

    <div class="row">
        <div class="col-md-6">
            <div class="card">
              <div class="card-header">
                  <h5 class="mb-0 h6">{{translate('Treasury Balance')}}</h5>
              </div>
              <div class="card-body">
                  <form class="form-horizontal" action="{{ route('transaction.post.uploaded_file') }}" method="POST" enctype="multipart/form-data">
                  	@csrf
                    <div class="form-group row">
                        <label class="col-lg-3 control-label">{{ translate('Upload File') }}</label>
                        <div class="col-lg-9">

                            <div class="custom-file">
                                <label class="custom-file-label">
                                    <input type="file" name="tran_file[]" class="custom-file-input" multiple accept="application/pdf" required>
                                    <span class="custom-file-name">{{ translate('Upload Files')}}</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-primary">{{translate('Save')}}</button>
                    </div>
                  </form>
              </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card bg-gray-light">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Please be carefully when you are uploading files.') }}</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group mar-no">
                        <li class="list-group-item text-dark">1. {{ translate('You Can choose Multi pdf files') }}.</li>
                        <li class="list-group-item text-dark">2. {{ translate('make sure name of every file like : transaction_id.pdf as 12345.pdf') }}.</li>
                    </ul>
                </div>
            </div>
        </div>

    </div>

@endsection
