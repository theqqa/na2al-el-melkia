<?php

namespace App\Http\Controllers;

use App\BusinessSetting;
use App\Models\CatchReceipt;
use App\Models\Expense;
use App\Models\PermissionExchange;
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
class PermissionExchangeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $expense_id =null;
        $date_range=null;
        $expense=Expense::all();

        $permission_exchanges = PermissionExchange::orderBy('created_at', 'desc');

        if ($request->has('expense_id') && $request->expense_id != "null"){
            $expense_id = $request->expense_id;
            $permission_exchanges = $permission_exchanges->where('expense_id', $expense_id);
        }

        if ($request->date_range) {
            $date_range = $request->date_range;
            $date_range1 = explode(" / ", $request->date_range);
            $permission_exchanges = $permission_exchanges->whereDate('date', '>=', $date_range1[0]);
            $permission_exchanges = $permission_exchanges->whereDate('date', '<=', $date_range1[1]);
        }
        $permission_exchanges = $permission_exchanges->paginate(15);
        return view('backend.permission_exchanges.index', compact('permission_exchanges', 'expense_id','date_range','expense'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $expense_lists=Expense::get();
        return view('backend.permission_exchanges.create',compact('expense_lists'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $permission_exchanges = new PermissionExchange();
        $permission_exchanges->date = $request->date;
        $permission_exchanges->expense_id = $request->expense_id;
        $permission_exchanges->price =$request->price;
        $permission_exchanges->expense_by = $request->expense_by;
        $permission_exchanges->description = $request->description;
       if ($permission_exchanges->save()) {

            flash(translate('Permission Exchanges has been inserted successfully'))->success();
            return redirect()->route('permission_exchanges.index');
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
        $expense_lists=Expense::get();
        $permission_exchange = PermissionExchange::findOrFail($id);
        return view('backend.permission_exchanges.show', compact('permission_exchange','expense_lists'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit( $id)
    {
        $expense_lists=Expense::get();
        $permission_exchange = PermissionExchange::findOrFail($id);
        return view('backend.permission_exchanges.edit', compact('permission_exchange','expense_lists'));
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
        $permission_exchanges = PermissionExchange::findOrFail($id);
        $permission_exchanges->date = $request->date;
        $permission_exchanges->expense_id = $request->expense_id;
        $permission_exchanges->price =$request->price;
        $permission_exchanges->expense_by = $request->expense_by;
        $permission_exchanges->description = $request->description;
      if(  $permission_exchanges->save()){

            flash(translate('Permission Exchange has been updated successfully'))->success();
            return redirect()->route('permission_exchanges.index');
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
        PermissionExchange::destroy($id);
        flash(translate('Permission Exchange has been deleted successfully'))->success();
        return redirect()->route('permission_exchanges.index');
    }

    public function update_status(Request $request)
    {
        $permission_exchange = PermissionExchange::findOrFail($request->id);
        $permission_exchange->approved = $request->status;
        if($permission_exchange->save()){
            flash(translate('Permission Exchange  updated successfully'))->success();
            return 1;
        }
        return 0;
    }
    public function confirm_exchanges(Request $request)
    {
        $permission_exchange = PermissionExchange::findOrFail($request->id);
        $treasury_balance = BusinessSetting::where('type', 'treasury_balance')->first();
        $treasury_balance_history= new  TreasuryBalanceHistory();
        $treasury_balance_history->permission_exchange_id=$permission_exchange->id;
        $treasury_balance_history->balance_before= $treasury_balance->value;
        $treasury_balance_history->balance_request=$permission_exchange->price;
        $treasury_balance_history->balance_after=$treasury_balance->value - $permission_exchange->price ;
        $treasury_balance_history->save();
        $treasury_balance->value= $treasury_balance->value - $permission_exchange->price ;
        $treasury_balance->save();
        $permission_exchange->status = 1;
        $permission_exchange->exchange_at =Carbon::now();

        if($permission_exchange->save()){
            flash(translate('Permission Exchange  updated successfully'))->success();
            return 1;
        }
        return 0;
    }
    public function permission_download($id)
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

//        if($currency_code == 'BDT' || $language_code == 'bd'){
//            // bengali font
//            $font_family = "'Hind Siliguri','sans-serif'";
//        }elseif($currency_code == 'KHR' || $language_code == 'kh'){
//            // khmer font
//            $font_family = "'Hanuman','sans-serif'";
//        }elseif($currency_code == 'AMD'){
//            // Armenia font
//            $font_family = "'arnamu','sans-serif'";
//        }elseif($currency_code == 'ILS'){
//            // Israeli font
//            $font_family = "'Varela Round','sans-serif'";
//        }elseif($currency_code == 'AED' || $currency_code == 'EGP'){
//            // middle east/arabic font
//            $font_family = "'XBRiyaz','sans-serif'";
//        }else{
//            // general for all
//            $font_family = "'Roboto','sans-serif'";
//        }
//        $font_family = "'XBRiyaz','sans-serif'";

        $permission = PermissionExchange::findOrFail($id);
        return PDF::loadView('backend.permission_exchanges.permission_print',[
            'permission' => $permission,
            'font_family' => $font_family,
            'direction' => $direction,
            'text_align' => $text_align,
            'not_text_align' => $not_text_align
        ], [], [])->download('permission-'.$permission->id.'.pdf');
    }

}
