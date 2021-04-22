<?php

namespace App\Http\Controllers;

use App\Models\Representative;
use App\Models\RepresentativeHistory;
use App\Models\Transaction;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\FlashDeal;
use App\FlashDealTranslation;
use App\FlashDealProduct;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort_search =null;
        $transactions = Transaction::orderBy('created_at', 'desc');
        if ($request->has('search')){
            $sort_search = $request->search;
            $transactions = $transactions->where('transaction_id', 'like', '%'.$sort_search.'%');
        }

        if(Auth::user()->user_type == 'staff' &&Auth::user()->staff->role_id==3)
        {
            $transactions = $transactions->where('user_id', Auth::user()->id);

        }
        $transactions = $transactions->paginate(15);
        return view('backend.transactions.index', compact('transactions', 'sort_search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $rep_lists=Representative::whereActive('1')->get();
        return view('backend.transactions.create',compact('rep_lists'));
    }
    public function uploaded_file()
    {

        return view('backend.transactions.upload_files');
    }
    public function post_uploaded_file(Request $request)
    {
       $files= $request->tran_file;
        if($request->hasFile('tran_file')) {
            foreach ($files as $key=>$val)
            {
                $file_name = explode(".", $val->getClientOriginalName());
                if (!empty($file_name[0])) {
                    $transaction = Transaction::where('transaction_id', 'like', '%' . $file_name[0] . '%')->first();
                    if (!empty($transaction)){
                        $transaction->files = $val->store('uploads/transactions/pdf');
                    if ($transaction->update()) {


                        flash(translate('Transaction Files has been inserted successfully'))->success();
//                        return redirect()->route('transactions.index');
                    }
//                    else {
//                        flash(translate('Something went wrong'))->error();
//                        return back();
//                    }
                }
                    else {
                        flash(translate('Can\'t find Related Transactions ,Make sure File name'))->error();
//                        return back();
                    }
                }
//
//                flash(translate('Something went wrong'))->error();
//                return back();
            }
            return redirect()->route('transactions.index');

        }

    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data=$request->all();

        Validator::make($data, [
            'transaction_id' => 'required|unique:transactions|max:255',
        ])->validate();
        $transaction = new Transaction();
        $transaction->transaction_id = $request->transaction_id;
        $transaction->representative_id = $request->representative_id;
        $transaction->sub_representative = $request->sub_representative;
        $transaction->user_id = Auth::id();
        $transaction->timedate =$request->register_at;
        $transaction->type = $request->type;
        $transaction->notes = $request->notes;
if ($transaction->save()) {
    $representative_data= $transaction->representative;
    $total=0;
    if($request->type==1)
    {
        $total += $representative_data->transfer_price;
    }
    elseif($request->type==2){
        $total  += $representative_data->renewal_price;
    }
    elseif($request->type==3){
        $total  +=  $representative_data->renewal_price+$representative_data->transfer_price;
    }
    $treasury_balance_history= new  RepresentativeHistory();
    $treasury_balance_history->rep_id= $request->representative_id;
    $treasury_balance_history->transaction_id= $transaction->id;
    $treasury_balance_history->deserved_amount_before= $representative_data->deserved_amount;
    $treasury_balance_history->deserved_amount_after=$representative_data->deserved_amount + $total ;
    $treasury_balance_history->deserved_amount_request=$total;
    $treasury_balance_history->save();

    $representative_data->deserved_amount +=$total;
    $representative_data->save();

            flash(translate('Transaction has been inserted successfully'))->success();
            return redirect()->route('transactions.index');
        }
        else{
            flash(translate('Something went wrong'))->error();
            return back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $rep_lists=Representative::whereActive('1')->get();

        $transaction = Transaction::findOrFail($id);
        return view('backend.transactions.edit', compact('transaction','rep_lists'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data=$request->all();

        Validator::make($data, [
            'transaction_id' => 'required|unique:transactions,transaction_id,'.$id,
        ])->validate();
        $transaction = Transaction::findOrFail($id);
        $transaction->sub_representative = $request->sub_representative;

        $transaction->transaction_id = $request->transaction_id;
        $transaction->representative_id = $request->representative_id;
        $transaction->user_id = Auth::id();
        $transaction->timedate =$request->register_at;
        $transaction->type = $request->type;
        $transaction->notes = $request->notes;
      if(  $transaction->save()){
          $representative_data= $transaction->representative;
          $total=0;
          if($request->type==1)
          {
              $total += $representative_data->transfer_price;
          }
          elseif($request->type==2){
              $total  += $representative_data->renewal_price;
          }
          elseif($request->type==3){
              $total  +=  $representative_data->renewal_price+$representative_data->transfer_price;
          }
          $representative_data->deserved_amount -= RepresentativeHistory::whereRepId($request->representative_id)->get()->last()->deserved_amount_request;

          $treasury_balance_history= new  RepresentativeHistory();
          $treasury_balance_history->rep_id= $request->representative_id;
          $treasury_balance_history->transaction_id= $transaction->id;
          $treasury_balance_history->deserved_amount_before= $representative_data->deserved_amount;
          $treasury_balance_history->deserved_amount_after=$representative_data->deserved_amount + $total ;
          $treasury_balance_history->deserved_amount_request=$total;
          $treasury_balance_history->save();

          $representative_data->deserved_amount +=$total;
          $representative_data->save();
            flash(translate('Transaction has been updated successfully'))->success();
            return redirect()->route('transactions.index');
        }
        else{
            flash(translate('Something went wrong'))->error();
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $transaction = Transaction::findOrFail($id);
        $representative_data= $transaction->representative;
        $total=0;
        if($transaction->type==1)
        {
            $total += $representative_data->transfer_price;
        }
        elseif($transaction->type==2){
            $total  += $representative_data->renewal_price;
        }
        elseif($transaction->type==3){
            $total  +=  $representative_data->renewal_price+$representative_data->transfer_price;
        }
        $treasury_balance_history=   RepresentativeHistory::whereRepId($transaction->representative_id)->where('transaction_id',$transaction->id)->get()->last();
        $treasury_balance_history->delete();

        $representative_data->deserved_amount -=$total;
        $representative_data->save();


        Transaction::destroy($id);
        flash(translate('Transaction has been deleted successfully'))->success();
        return redirect()->route('transactions.index');
    }

    public function update_status(Request $request)
    {
//        $flash_deal = FlashDeal::findOrFail($request->id);
//        $flash_deal->status = $request->status;
//        if($flash_deal->save()){
//            flash(translate('Flash deal status updated successfully'))->success();
//            return 1;
//        }
//        return 0;
    }

    public function transactions_user_board(Request $request)
    {
        $user = User::find($request->user_id);
        Auth::login($user);
        return redirect()->route('admin.dashboard');
    }
}
