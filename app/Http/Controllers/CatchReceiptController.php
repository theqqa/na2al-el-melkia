<?php

namespace App\Http\Controllers;

use App\BusinessSetting;
use App\Models\CatchReceipt;
use App\Models\Representative;
use App\Models\RepresentativeHistory;
use App\Models\Transaction;
use App\Models\TreasuryBalanceHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\FlashDeal;
use App\FlashDealTranslation;
use App\FlashDealProduct;
use Illuminate\Support\Str;
use Session;
use PDF;
use Auth;
use Config;
use Illuminate\Database\Eloquent\Builder;

class CatchReceiptController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort_search =null;
        $date_range=null;
        $catch_receipts = CatchReceipt::orderBy('created_at', 'desc');
        if ($request->date_range) {
            $date_range = $request->date_range;
            $date_range1 = explode(" / ", $request->date_range);
            $catch_receipts = $catch_receipts->whereDate('date', '>=', $date_range1[0]);
            $catch_receipts = $catch_receipts->whereDate('date', '<=', $date_range1[1]);
        }
        if ($request->has('search')){
            $sort_search = $request->search;
            $catch_receipts = $catch_receipts->whereHas('representative',  function (Builder $query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%');
            });

        }

        $catch_receipts = $catch_receipts->paginate(15);
        return view('backend.catch_receipts.index', compact('catch_receipts', 'sort_search','date_range'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $rep_lists=Representative::whereActive('1')->get();
        return view('backend.catch_receipts.create',compact('rep_lists'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $catch_receipt = new CatchReceipt();
        $catch_receipt->date = $request->date;
//        $catch_receipt->code = $request->code;
        $catch_receipt->representative_id = $request->representative_id;
        $catch_receipt->price =$request->price;
        $catch_receipt->payment_by = $request->payment_by;
        $catch_receipt->description = $request->description;
if ($catch_receipt->save()) {
    $catch_receipt->code = 'Cr-'.$catch_receipt->id;
    $catch_receipt->save();
    $treasury_balance = BusinessSetting::where('type', 'treasury_balance')->first();
    $treasury_balance_history= new  TreasuryBalanceHistory();
    $treasury_balance_history->catch_receipt_id=$catch_receipt->id;
    $treasury_balance_history->balance_before= $treasury_balance->value;
    $treasury_balance_history->balance_request=$catch_receipt->price;
    $treasury_balance_history->balance_after=$treasury_balance->value + $catch_receipt->price ;
    $treasury_balance_history->save();
    $treasury_balance->value= $treasury_balance->value + $catch_receipt->price ;
    $treasury_balance->save();
    $treasury_balance_history= new  RepresentativeHistory();
    $treasury_balance_history->rep_id= $catch_receipt->representative->id;
    $treasury_balance_history->catch_receipt_id= $catch_receipt->id;
    $treasury_balance_history->deserved_amount_before= $catch_receipt->representative->deserved_amount;
    $treasury_balance_history->deserved_amount_after=$catch_receipt->representative->deserved_amount - $catch_receipt->price ;
    $treasury_balance_history->deserved_amount_request=$catch_receipt->price;
    $treasury_balance_history->save();
    $catch_receipt->representative->deserved_amount=  $catch_receipt->representative->deserved_amount - $catch_receipt->price ;
    $catch_receipt->representative->save();
            flash(translate('Catch Receipt has been inserted successfully'))->success();
            return redirect()->route('catch_receipts.index');
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
        $rep_lists=Representative::whereActive('1')->get();

        $catch_receipt = CatchReceipt::findOrFail($id);
        return view('backend.catch_receipts.show', compact('catch_receipt','rep_lists'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit( $id)
    {
        $rep_lists=Representative::whereActive('1')->get();

        $catch_receipt = CatchReceipt::findOrFail($id);
        return view('backend.catch_receipts.edit', compact('catch_receipt','rep_lists'));
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
        $catch_receipt_old = CatchReceipt::findOrFail($id);


        $treasury_balance = BusinessSetting::where('type', 'treasury_balance')->first();
        $treasury_balance->value= $treasury_balance->value-$catch_receipt_old->price ;
        $treasury_balance->save();
        $treasury_balance_history_old= TreasuryBalanceHistory::where('catch_receipt_id',$catch_receipt_old->id)->first();

        $treasury_balance_history_old->balance_before= $treasury_balance->value;
        $treasury_balance_history_old->balance_request=$request->price;
        $treasury_balance_history_old->balance_after=$treasury_balance->value + $request->price ;
        $treasury_balance_history_old->save();

        $treasury_balance->value= $treasury_balance->value + $request->price ;
        $treasury_balance->save();
        $treasury_balance_history_old= RepresentativeHistory::whereRepId($catch_receipt_old->representative->id)->where('catch_receipt_id',$catch_receipt_old->id)->first();
        $catch_receipt_old->representative->deserved_amount=  $catch_receipt_old->representative->deserved_amount + $catch_receipt_old->price ;
        $catch_receipt_old->representative->save();

        $treasury_balance_history_old->deserved_amount_before= $catch_receipt_old->representative->deserved_amount;
        $treasury_balance_history_old->deserved_amount_after=$catch_receipt_old->representative->deserved_amount - $request->price ;
        $treasury_balance_history_old->deserved_amount_request=$request->price;
        $treasury_balance_history_old->save();
        $catch_receipt_old->representative->deserved_amount=  $catch_receipt_old->representative->deserved_amount - $request->price ;
        $catch_receipt_old->representative->save();



        $catch_receipt_old->date = $request->date;
        $catch_receipt_old->price =$request-> price;
        $catch_receipt_old->payment_by = $request->payment_by;
        $catch_receipt_old->description = $request->description ;
      if(  $catch_receipt_old->save()){

            flash(translate('Catch receipt has been updated successfully'))->success();
            return redirect()->route('catch_receipts.index');
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

    public function catch_receipt_download($id)
    {
        if(Session::has('currency_code')){
            $currency_code = Session::get('currency_code');
        }
        else{
            $currency_code = \App\Currency::findOrFail(get_setting('system_default_currency'))->code;
        }
        $language_code = Session::get('locale', Config::get('app.locale'));

        if(\App\Language::where('code', $language_code)->first()->rtl == 1){
            $direction = 'rtl';
            $text_align = 'right';
            $not_text_align = 'left';
            $font_family = "'XBRiyaz','sans-serif'";
        }else{
            $direction = 'ltr';
            $text_align = 'left';
            $not_text_align = 'right';
            $font_family = "'Roboto','sans-serif'";

        }

        $catch_receipt = CatchReceipt::findOrFail($id);
        return PDF::loadView('backend.catch_receipts.catch_receipts_print',[
            'catch_receipt' => $catch_receipt,
            'font_family' => $font_family,
            'direction' => $direction,
            'text_align' => $text_align,
            'not_text_align' => $not_text_align
        ], [], [])->download('catch_receipt-'.$catch_receipt->id.'.pdf');
    }
    public function update_status(Request $request)
    {
        $catch_receipt = CatchReceipt::findOrFail($request->id);
        $catch_receipt->approved = $request->status;
        if($catch_receipt->save()){
            $treasury_balance = BusinessSetting::where('type', 'treasury_balance')->first();
            $treasury_balance_history= new  TreasuryBalanceHistory();
            $treasury_balance_history->catch_receipt_id=$catch_receipt->id;
            $treasury_balance_history->balance_before= $treasury_balance->value;
            $treasury_balance_history->balance_request=$catch_receipt->price;
            $treasury_balance_history->balance_after=$treasury_balance->value + $catch_receipt->price ;
            $treasury_balance_history->save();
            $treasury_balance->value= $treasury_balance->value + $catch_receipt->price ;
            $treasury_balance->save();
            $treasury_balance_history= new  RepresentativeHistory();
            $treasury_balance_history->rep_id= $catch_receipt->representative->id;
            $treasury_balance_history->catch_receipt_id= $catch_receipt->id;
            $treasury_balance_history->deserved_amount_before= $catch_receipt->representative->deserved_amount;
            $treasury_balance_history->deserved_amount_after=$catch_receipt->representative->deserved_amount - $catch_receipt->price ;
            $treasury_balance_history->deserved_amount_request=$catch_receipt->price;
            $treasury_balance_history->save();
            $catch_receipt->representative->deserved_amount=  $catch_receipt->representative->deserved_amount - $catch_receipt->price ;
            $catch_receipt->representative->save();
            flash(translate('Catch Receipt  updated successfully'))->success();
            return 1;
        }
        return 0;
    }

}
