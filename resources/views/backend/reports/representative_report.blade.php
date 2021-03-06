@extends('backend.layouts.app')

@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class=" align-items-center">
       <h1 class="h3">{{translate('Representative Report')}}</h1>
	</div>
</div>

<div class="row" >
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('users_representative_report.index') }}" method="GET">




                    <div class="card-header row gutters-5">
                        <div class="col text-center text-md-left">
                            <h5 class="mb-md-0 h6">{{ translate('Sort by Representative') }}</h5>
                        </div>

                            <div class="col-md-3 ml-auto">
                                <select class="from-control aiz-selectpicker" name="rep_id" data-live-search="true"  required >
                                    <option value="null" @if(empty($rep_id ) )selected @endif>{{ translate('All') }}</option>
                                    @foreach($rep as $key=>$val)
                                        <option value="{{$val->id}}" @if($rep_id == $val->id) selected @endif>{{ $val->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                        <div class="col-md-3 ml-auto">
                            <select class="from-control aiz-selectpicker" name="code" data-live-search="true"  required >
                                <option value="null" @if(empty($code_id ) )selected @endif>{{ translate('All Code') }}</option>
                                @foreach($codes as $key=>$val)
                                    <option value="{{$val}}" @if( $code_id== $val) selected @endif>{{ $val }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group mb-0">
                                <input type="text" class="form-control form-control-sm aiz-date-range" id="search" name="date_range" autocomplete="off" @isset($date_range) value="{{ $date_range }}" @endisset placeholder="{{ translate('Daterange') }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-md btn-primary" type="submit">
                                {{ translate('Filter') }}
                            </button>
                        </div>
                    </div>


</form>
                <div  id="divName">

                @if(!empty($rep_id) && $rep_id != null)

                    <div class="card-header row ">
                        <?php   $rep =   \App\Models\Representative::find($rep_id); ?>
                        <span class="btn-soft-success aiz-side-nav-text">{{translate('Representative Name')}} :{{$rep->name }} </span>
                        <span class="btn-soft-info aiz-side-nav-text">{{translate('Representative Code')}} :{{$rep->code }} </span>


                    </div>

                    <br>
                    <?php   $rep =   \App\Models\Representative::find($rep_id); ?>
                    <h5 class="mb-md-0 h6 col-6 ">{{translate('Representative Code')}} : {{ $rep->code }}</h5>
                    <h5 class="mb-md-0 h6 col-6 ">{{translate('Representative Name')}} : {{ $rep->name }}</h5>
                    <h5 class="mb-md-0 h6 col-6 ">{{translate('Initial Balance')}} : {{ $rep->initial_balance }} <span class="small">????????</span></h5>
                    <h5 class="mb-md-0 h6 col-6 ">{{translate('Required Balance')}} : {{ $rep->deserved_amount }} <span class="small">????????</span></h5>
                    <h5 class="mb-md-0 h6 col-6 ">{{translate('Paid Balance')}} : {{ $paid_hist }} <span class="small">????????</span></h5>

                    @if($pre_total !=null)
                        <h5 class="mb-md-0 h6 col-6 ">{{translate('Previous Balance')}} : {{$pre_total_balance+$rep->initial_balance }} <span class="small">????????</span></h5>

                    @endif
                    <br>
                @endif
<table class="table table-bordered  mb-0">
<thead>
  <tr>
    <th>#</th>
      <th data-breakpoints="lg">{{ translate('Time Date') }}</th>
      <th data-breakpoints="lg">{{ translate('Representative Name') }}</th>
      <th>{{translate('Service Type')}}</th>
      <th>{{translate('Transactions Id').'/'.translate('Code Catch receipts')}}</th>
{{--      <th data-breakpoints="lg">{{ translate('Price Before') }}</th>--}}
      <th data-breakpoints="lg">{{ translate('Price') }}</th>
      <th data-breakpoints="lg">{{ translate('Balance') }}</th>
</thead>

<tbody>
<?php
$count_ownership =0;
$count_renewal =0;
$total_2=0;
$codes=[];
$total_balance=!empty($rep->initial_balance)?$rep->initial_balance:0;
?>
  @foreach ($rep_hists as $key => $val)

          <tr>

              <td>{{$key+1}}</td>
              <td>{{ $val->created_at }}</td>

              <td >{{ $val->representative->name }}</td>
              @if(!empty($val->catch_receipt_id))
              <td>
                  <a href="{{route('catch_receipts.edit',$val->catchReceipt->id )}}">{{translate('Catch Receipt')}}</a></td>
              @elseif(!empty($val->transaction_id)&&!empty($val->transaction))
                  @if ($val->transaction->type==1)
<?php
                      $count_ownership += 1;
?>
                      <td><a href="{{route('transactions.index')}}">{{translate('Ownership')}}</a></td>

                  @elseif($val->transaction->type==2)
                      <?php

                      $count_renewal +=1;
                      ?>

                      <td><a href="{{route('transactions.index')}}">{{translate('Renewal')}}</a></td>

                  @elseif($val->transaction->type==3)
                      <?php
                      $count_ownership += 1;
                      $count_renewal +=1;
                      ?>
              <td><a href="{{route('transactions.index')}}">{{translate('Both')}}</a></td>
                  @endif

              @endif
              @if(!empty($val->catch_receipt_id))
                  <?php $codes[]=$val->catchReceipt->code ?>
              <td>{{$val->catchReceipt->code}}</td>
              @elseif(!empty($val->transaction_id))
                  <?php $codes[]=$val->transaction->transaction_id ?>

                  <td>{{$val->transaction->transaction_id}}</td>
              @endif
{{--              <td>{{ $val->deserved_amount_before}}</td>--}}
              @if(!empty($val->catch_receipt_id))
              <td style="color: red">{{ '- ' .$val->deserved_amount_request}}</td>
              @elseif(!empty($val->transaction_id))
                  <?php $total_2 += $val->deserved_amount_request?>
                  <td>{{ $val->deserved_amount_request}}</td>

              @endif
{{--              $total_balance+= $val->deserved_amount_reques--}}
              @if(!empty($val->catch_receipt_id))

              <td>{{ $total_balance-= $val->deserved_amount_request}}</td>
              @elseif(!empty($val->transaction_id))
                            <td>{{ $total_balance+= $val->deserved_amount_request}}</td>

              @endif

          </tr>
          @if($loop->last)
              <tr style="font-weight: bold">
                  <td colspan="6">{{translate('Ownership Count')}}</td>
                  <td colspan="2" class="btn-soft-success aiz-side-nav-text"> <span class="small "> {{$count_ownership}}</span> </td>

              </tr>
              <tr style="font-weight: bold">
                  <td colspan="6">{{translate('Renewal Count')}}</td>
                  <td colspan="2" class="btn-soft-success aiz-side-nav-text"> <span class="small  "> {{$count_renewal}}</span> </td>



              </tr>
              <tr style="font-weight: bold">
                  <td colspan="6">{{translate('Total2')}}</td>
                  <td colspan="2" class="btn-soft-success aiz-side-nav-text"> {{$total_2}} <span class="small"> ????????</span> </td>

              </tr>
{{--              <tr style="font-weight: bold">--}}
{{--                  <td colspan="5">{{translate('Tax')}}</td>--}}
{{--                  <td colspan="2" class="btn-soft-success aiz-side-nav-text">15 <span class="small">%</span> </td>--}}

{{--              </tr>--}}
{{--              <tr style="font-weight: bold">--}}
{{--                  <td colspan="5">{{translate('Total')}}</td>--}}
{{--                  <td colspan="2" class="btn-soft-success aiz-side-nav-text">{{$total_2+.15*$total_2}} <span class="small">???????? </span> </td>--}}

{{--              </tr>--}}
          @endif
  @endforeach

</tbody>
</table>
                </div>
{{--                <div class="aiz-pagination mt-4">--}}
{{--                    {{ $rep_hists->links() }}--}}
{{--                </div>--}}
                <button class="btn btn-md btn-success "  onClick="printDiv();" id="print_id">   <span  class=" las la-print"> {{ translate('Print') }}</span> </button>
                @if(!empty($rep_id ) )
                <button class="btn btn-md btn-info "  onClick="sendDiv({{$rep_id}});" >   <span  class=" las la-paper-plane"> {{ translate('send') }}</span> </button>

@endif
            </div>
</div>
</div>
</div>

@endsection

@section('script')
    <script type="text/javascript">
        function printDiv() {
            var printContents = document.getElementById("divName").innerHTML;
            var originalContents = document.body.innerHTML;
            document.getElementById('sidenav').style.display = 'none';
            document.getElementById('nav').style.display = 'none';

            document.body.innerHTML = printContents;

            window.print();


            document.body.innerHTML = originalContents;
        }
        function sendDiv($rep) {
            var printContents = document.getElementById("divName").innerHTML;
            var originalContents = document.body.innerHTML;
            document.getElementById('sidenav').style.display = 'none';
            document.getElementById('nav').style.display = 'none';

            $content = printContents;

           console.log($content);
            $.post('{{ route('send_mail_representative') }}', {_token: AIZ.data.csrf, content_page:$content ,rep_id:$rep}, function(data){
                if(data == 1){
                    AIZ.plugins.notify('success', '{{ translate('Email send successfully') }}');
                }
                else{
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });

            document.body.innerHTML = originalContents;
        }
    </script>
@endsection
