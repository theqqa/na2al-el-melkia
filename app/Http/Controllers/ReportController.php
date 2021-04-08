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
use App\Staff;
use Aws\History;
use Illuminate\Http\Request;
use App\Product;
use App\CommissionHistory;
use App\Seller;
use App\User;
use App\Search;
use Auth;

class ReportController extends Controller
{
    public function users_transactions_report(Request $request)
    {
        $user_id =null;
        $date_range=null;
        $users = Staff::whereRoleId('3')->get();

        $transactions = Transaction::orderBy('created_at', 'desc');
        if ($request->has('user_id') && $request->user_id != "null"){
            $user_id = $request->user_id;
            $transactions = $transactions->where('user_id', $user_id);
        }
        if ($request->date_range) {
            $date_range = $request->date_range;
            $date_range1 = explode(" / ", $request->date_range);
            $transactions = $transactions->whereDate('timedate', '>=', $date_range1[0]);
            $transactions = $transactions->whereDate('timedate', '<=', $date_range1[1]);
        }
        $transactions = $transactions->get();
        $transactions_count=$transactions->count();
        $transactions = $transactions->groupBy('representative_id');

        $total_all=0;
     foreach ($transactions as $key_1 => $val)
        {
            $total_1[$key_1]=0;
            foreach ($val as $key_2 => $transaction){
                if ($transaction->type==1){
                    $total_1[$key_1]+=$transaction->representative->transfer_price ;
                    $total_all+=$transaction->representative->transfer_price ;
                }
                elseif($transaction->type==2){
                    $total_1[$key_1]+=$transaction->representative->renewal_price ;
                    $total_all+=$transaction->representative->renewal_price ;

                }elseif($transaction->type==3){
                    $total_1[$key_1]+=$transaction->representative->renewal_price+$transaction->representative->transfer_price ;
                    $total_all+=$transaction->representative->renewal_price+$transaction->representative->transfer_price ;
                }
        }
        }
        return view('backend.reports.users_transactions_report', compact('transactions','user_id','date_range','users','total_1','total_all','transactions_count'));
    }
    public function users_representative_report(Request $request)
    {
        $rep_id =null;
        $rep=Representative::all();
                $date_range=null;

        $rep_hists = RepresentativeHistory::orderBy('created_at', 'desc');
        if ($request->has('rep_id') && $request->rep_id != "null"){
            $rep_id = $request->rep_id;
            $rep_hists = $rep_hists->where('rep_id', $rep_id);
        }
        if ($request->date_range) {
            $date_range = $request->date_range;
            $date_range1 = explode(" / ", $request->date_range);
            $rep_hists = $rep_hists->whereDate('created_at', '>=', $date_range1[0]);
            $rep_hists = $rep_hists->whereDate('created_at', '<=', $date_range1[1]);
        }
        $rep_hists = $rep_hists->paginate(15);
        return view('backend.reports.representative_report', compact('rep_hists','rep_id','date_range','rep'));
    }

    public function catch_receipts_report(Request $request)
    {
        $rep_id =null;
         $date_range=null;

        $rep=Representative::all();
        $catch_receipts = CatchReceipt::orderBy('created_at', 'desc');
        if ($request->has('rep_id') && $request->rep_id != "null"){
            $rep_id = $request->rep_id;
            $catch_receipts = $catch_receipts->where('representative_id', $rep_id);
        }
        if ($request->date_range) {
            $date_range = $request->date_range;
            $date_range1 = explode(" / ", $request->date_range);
            $catch_receipts = $catch_receipts->whereDate('date', '>=', $date_range1[0]);
            $catch_receipts = $catch_receipts->whereDate('date', '<=', $date_range1[1]);
        }
        $catch_receipts = $catch_receipts->paginate(15);
        return view('backend.reports.catch_receipts_report', compact('catch_receipts','rep_id','date_range','rep'));
    }
    public function permission_exchanges_report(Request $request)
    {
        $expense_id=null;
        $expense=Expense::all();
                        $date_range=null;

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
        return view('backend.reports.permission_exchanges_report', compact('permission_exchanges','expense_id','date_range','expense'));
    }
    public function treasury_balance_report(Request $request)
    {
        $treasury_balances = TreasuryBalanceHistory::orderBy('created_at', 'desc');
                        $date_range=null;

        if ($request->date_range) {
            $date_range = $request->date_range;
            $date_range1 = explode(" / ", $request->date_range);
            $treasury_balances = $treasury_balances->whereDate('created_at', '>=', $date_range1[0]);
            $treasury_balances = $treasury_balances->whereDate('created_at', '<=', $date_range1[1]);
        }
        $treasury_balances = $treasury_balances->paginate(15);
        $business_settings = BusinessSetting::where('type', 'treasury_balance')->first();

        return view('backend.reports.treasury_balance_report', compact('treasury_balances','date_range','business_settings'));
    }
    public function users_taam_report(Request $request)
    {
        $user_id =null;
        $date_range=null;
                        $date_range=null;

        $users = Staff::whereRoleId('3')->get();

        $business_settings_renewal = BusinessSetting::where('type', 'taam_expenses_renewal')->first();
        $business_settings_ownership = BusinessSetting::where('type', 'taam_expenses_ownership')->first();

        $transactions = Transaction::orderBy('created_at', 'desc');
        if ($request->has('user_id') && $request->user_id != "null"){
            $user_id = $request->user_id;
            $transactions = $transactions->where('user_id', $user_id);
        }
        if ($request->date_range) {
            $date_range = $request->date_range;
            $date_range1 = explode(" / ", $request->date_range);
            $transactions = $transactions->whereDate('timedate', '>=', $date_range1[0]);
            $transactions = $transactions->whereDate('timedate', '<=', $date_range1[1]);
        }
        $transactions = $transactions->get();
        $transactions_count=$transactions->count();
        $transactions = $transactions->groupBy('representative_id');

        $total_all=0;
        foreach ($transactions as $key_1 => $val)
        {
            foreach ($val as $key_2 => $transaction){
                if ($transaction->type==1){
                    $total_all+=$business_settings_ownership->value;
                }
                elseif($transaction->type==2){
                    $total_all+=$business_settings_renewal->value ;

                }elseif($transaction->type==3){
                    $total_all+=$business_settings_renewal->value +$business_settings_ownership->value ;
                }
            }
        }
        return view('backend.reports.users_taam_report', compact('transactions','business_settings_renewal','business_settings_ownership','user_id','date_range','users','total_all','transactions_count'));
    }
    public function stock_report(Request $request)
    {
        $sort_by =null;
        $products = Product::orderBy('created_at', 'desc');
        if ($request->has('category_id')){
            $sort_by = $request->category_id;
            $products = $products->where('category_id', $sort_by);
        }
        $products = $products->paginate(15);
        return view('backend.reports.stock_report', compact('products','sort_by'));
    }

    public function in_house_sale_report(Request $request)
    {
        $sort_by =null;
        $products = Product::orderBy('num_of_sale', 'desc')->where('added_by', 'admin');
        if ($request->has('category_id')){
            $sort_by = $request->category_id;
            $products = $products->where('category_id', $sort_by);
        }
        $products = $products->paginate(15);
        return view('backend.reports.in_house_sale_report', compact('products','sort_by'));
    }

    public function seller_sale_report(Request $request)
    {
        $sort_by =null;
        $sellers = Seller::orderBy('created_at', 'desc');
        if ($request->has('verification_status')){
            $sort_by = $request->verification_status;
            $sellers = $sellers->where('verification_status', $sort_by);
        }
        $sellers = $sellers->paginate(10);
        return view('backend.reports.seller_sale_report', compact('sellers','sort_by'));
    }

    public function wish_report(Request $request)
    {
        $sort_by =null;
        $products = Product::orderBy('created_at', 'desc');
        if ($request->has('category_id')){
            $sort_by = $request->category_id;
            $products = $products->where('category_id', $sort_by);
        }
        $products = $products->paginate(10);
        return view('backend.reports.wish_report', compact('products','sort_by'));
    }

    public function user_search_report(Request $request){
        $searches = Search::orderBy('count', 'desc')->paginate(10);
        return view('backend.reports.user_search_report', compact('searches'));
    }

    public function commission_history(Request $request) {
        $seller_id = null;
        $date_range = null;

        if(Auth::user()->user_type == 'seller') {
            $seller_id = $request->seller_id;
        } if($request->seller_id) {
            $seller_id = $request->seller_id;
        }

        $commission_history = CommissionHistory::orderBy('created_at', 'desc');

        if ($request->date_range) {
            $date_range = $request->date_range;
            $date_range1 = explode(" / ", $request->date_range);
            $commission_history = $commission_history->where('created_at', '>=', $date_range1[0]);
            $commission_history = $commission_history->where('created_at', '<=', $date_range1[1]);
        }
        if ($seller_id){

            $commission_history = $commission_history->where('seller_id', '=', $seller_id);
        }

        $commission_history = $commission_history->paginate(10);
        if(Auth::user()->user_type == 'seller') {
            return view('frontend.user.seller.reports.commission_history_report', compact('commission_history', 'seller_id', 'date_range'));
        }
        return view('backend.reports.commission_history_report', compact('commission_history', 'seller_id', 'date_range'));
    }
}
