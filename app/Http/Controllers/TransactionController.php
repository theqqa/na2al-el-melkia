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
        $transaction->user_id = Auth::id();
        $transaction->timedate = Carbon::now();
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

        $transaction->transaction_id = $request->transaction_id;
        $transaction->representative_id = $request->representative_id;
        $transaction->user_id = Auth::id();
        $transaction->timedate =Carbon::now() ;
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
          $representative_data->deserved_amount -= RepresentativeHistory::whereRepId($request->representative_id)->last()->deserved_amount_request;

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
//        $flash_deal = FlashDeal::findOrFail($id);
//        foreach ($flash_deal->flash_deal_products as $key => $flash_deal_product) {
//            $flash_deal_product->delete();
//        }
//
//        foreach ($flash_deal->flash_deal_translations as $key => $flash_deal_translation) {
//            $flash_deal_translation->delete();
//        }
//
//        FlashDeal::destroy($id);
//        flash(translate('FlashDeal has been deleted successfully'))->success();
//        return redirect()->route('flash_deals.index');
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
