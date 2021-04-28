<?php

namespace App\Http\Controllers;

use App\BusinessSetting;
use App\Mail\EmailManager;
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
use Illuminate\Support\Facades\Mail;

class ReportController extends Controller
{
    public function users_transactions_report(Request $request)
    {
        $user_id = null;
        $date_range = null;
        $pre_transactions = [];
        $users = Staff::whereRoleId('3')->get();
        $business_settings = BusinessSetting::where('type', 'initial_treasury_balance')->first();
        $initial_treasury_balance = $business_settings->value;
        $transactions = Transaction::orderBy('created_at', 'desc');
        if ($request->has('user_id') && $request->user_id != "null") {
            $user_id = $request->user_id;
            $transactions = $transactions->where('user_id', $user_id);
        }
        if ($request->date_range) {
            $date_range = $request->date_range;
            $date_range1 = explode(" / ", $request->date_range);
            $pre_transactions = Transaction::whereDate('timedate', '<', $date_range1[0])->get();
            $transactions = $transactions->whereDate('timedate', '>=', $date_range1[0]);
            $transactions = $transactions->whereDate('timedate', '<=', $date_range1[1]);
        }
        $transactions = $transactions->get();
        $transactions_count = $transactions->count();
//        $transactions = $transactions->groupBy('representative_id');

        $total_all = 0;;
//     foreach ($transactions as $key_1 => $val)
//        {
        $total_1[] = 0;
        $sum[] = 0;
        $count_ownership_res[] = 0;
        $count_renewal_res[] = 0;
        $count_owner = 0;
        $count_renewal = 0;
        $total_pre = 0;
        foreach ($pre_transactions as $key_pre => $val_pre) {
            if ($val_pre->type == 1) {
                $total_pre += $val_pre->representative->transfer_price;
            } elseif ($val_pre->type == 2) {
                $total_pre += $val_pre->representative->renewal_price;


            } elseif ($val_pre->type == 3) {

                $total_pre += $val_pre->representative->renewal_price + $val_pre->representative->transfer_price;
            }
        }

        foreach ($transactions as $key => $transaction) {
            if ($transaction->type == 1) {
                $count_owner += 1;
                $total_1[$key] = $transaction->representative->transfer_price;
                $total_all += $transaction->representative->transfer_price;
                $count_ownership_res[$transaction->representative->name] = 0;

            } elseif ($transaction->type == 2) {
                $count_renewal += 1;

                $total_1[$key] = $transaction->representative->renewal_price;
                $total_all += $transaction->representative->renewal_price;
                $count_renewal_res[$transaction->representative->name] = 0;


            } elseif ($transaction->type == 3) {
                $count_renewal += 1;
                $count_owner += 1;

                $total_1[$key] = $transaction->representative->renewal_price + $transaction->representative->transfer_price;
                $total_all += $transaction->representative->renewal_price + $transaction->representative->transfer_price;
                $count_ownership_res[$transaction->representative->name] = 0;
                $count_renewal_res[$transaction->representative->name] = 0;


            }
        }
        unset($count_renewal_res[0]);
        unset($count_ownership_res[0]);

        foreach ($total_1 as $key_1 => $value) {
            if ($key_1 != 0)
                $sum[$key_1] = $sum[$key_1 - 1] + $value;
            else
                $sum[$key_1] = $value;
        }
        return view('backend.reports.users_transactions_report', compact('count_ownership_res', 'count_renewal_res', 'total_pre', 'initial_treasury_balance', 'count_renewal', 'count_owner', 'transactions', 'sum', 'user_id', 'date_range', 'users', 'total_1', 'total_all', 'transactions_count'));
    }

    public function users_representative_report(Request $request)
    {
        $rep_id = null;
        $rep = Representative::all();
        $date_range = null;
        $paid_hist = 0;
        $count_ownership = 0;
        $count_renewal = 0;
        $code_id = null;
        $pre_total = null;
        $codes = [];
        $pre_total_balance=0;
        $rep_hists = RepresentativeHistory::orderBy('created_at', 'asc');

        if ($request->has('rep_id') && $request->rep_id != "null") {
            $rep_id = $request->rep_id;

            $rep_hists = $rep_hists->where('rep_id', $rep_id);
            $paid_hist = RepresentativeHistory::where('rep_id', $rep_id)->whereNotNull('catch_receipt_id')->sum('deserved_amount_request');

        }
        if ($request->has('code') && $request->code != "null") {
            $code_id = $request->code;
            $rep_hists = $rep_hists->whereHas('transaction', function ($q) use ($code_id) {
                $q->where('transaction_id', $code_id);
            })->orWhereHas('catchReceipt', function ($q) use ($code_id) {
                $q->where('code', $code_id);
            });

        }
        if ($request->date_range) {
            $date_range = $request->date_range;
            $date_range1 = explode(" / ", $request->date_range);

            $rep_hists = $rep_hists->whereDate('created_at', '>=', $date_range1[0]);
            $rep_hists = $rep_hists->whereDate('created_at', '<=', $date_range1[1]);
            $pre_total = RepresentativeHistory::whereDate('created_at', '<', $date_range1[0]);
            if ($request->has('rep_id') && $request->rep_id != "null") {
                $pre_total = $pre_total->where('rep_id', $request->rep_id)->get();
            } else {
                $pre_total = $pre_total->get();

            }
            foreach ($pre_total as $key_1 => $val_1){
                if(!empty($val_1->catch_receipt_id))

             $pre_total_balance-= $val_1->deserved_amount_request;
                elseif(!empty($val_1->transaction_id)){
                    $pre_total_balance+= $val_1->deserved_amount_request;

                }

            }


        }
        $rep_hists = $rep_hists->get();

        foreach ($rep_hists as $key => $val) {
            if (!empty($val->catch_receipt_id))
                $codes[] = $val->catchReceipt->code;

            elseif (!empty($val->transaction_id))

                $codes[] = !empty($val->transaction)?$val->transaction->transaction_id:'';




        }

        $codes = array_filter($codes);
        return view('backend.reports.representative_report', compact('pre_total_balance','code_id', 'count_ownership', 'codes', 'pre_total', 'count_renewal', 'paid_hist', 'rep_hists', 'rep_id', 'date_range', 'rep'));
    }

    public function catch_receipts_report(Request $request)
    {
        $rep_id = null;
        $date_range = null;
        $total_price = 0;
        $rep = Representative::all();
        $catch_receipts = CatchReceipt::orderBy('created_at', 'desc');
        if ($request->has('rep_id') && $request->rep_id != "null") {
            $rep_id = $request->rep_id;
            $catch_receipts = $catch_receipts->where('representative_id', $rep_id);
        }
        if ($request->date_range) {
            $date_range = $request->date_range;
            $date_range1 = explode(" / ", $request->date_range);
            $catch_receipts = $catch_receipts->whereDate('date', '>=', $date_range1[0]);
            $catch_receipts = $catch_receipts->whereDate('date', '<=', $date_range1[1]);
        }
        if ($request->has('rep_id') && $request->rep_id != "null") {
            $total_price = $catch_receipts->sum('price');
        } else {
            $total_price = $catch_receipts->sum('price');


        }

        $catch_receipts = $catch_receipts->get();
        return view('backend.reports.catch_receipts_report', compact('total_price', 'catch_receipts', 'rep_id', 'date_range', 'rep'));
    }

    public function permission_exchanges_report(Request $request)
    {
        $expense_id = null;
        $expense = Expense::all();
        $date_range = null;

        $permission_exchanges = PermissionExchange::orderBy('created_at', 'desc');
        if ($request->has('expense_id') && $request->expense_id != "null") {
            $expense_id = $request->expense_id;
            $permission_exchanges = $permission_exchanges->where('expense_id', $expense_id);
        }

        if ($request->date_range) {
            $date_range = $request->date_range;
            $date_range1 = explode(" / ", $request->date_range);
            $permission_exchanges = $permission_exchanges->whereDate('date', '>=', $date_range1[0]);
            $permission_exchanges = $permission_exchanges->whereDate('date', '<=', $date_range1[1]);
        }
        $total_price = $permission_exchanges->sum('price');

        $permission_exchanges = $permission_exchanges->get();
        return view('backend.reports.permission_exchanges_report', compact('permission_exchanges', 'total_price', 'expense_id', 'date_range', 'expense'));
    }

    public function treasury_balance_report(Request $request)
    {
        $business_settings = BusinessSetting::where('type', 'treasury_balance')->first();
        $business_settings_initial = BusinessSetting::where('type', 'initial_treasury_balance')->first();

        $treasury_balances = TreasuryBalanceHistory::orderBy('created_at', 'asc');
        $date_range = null;
        $changed_treasury = 0;
        if ($request->date_range) {
            $date_range = $request->date_range;
            $date_range1 = explode(" / ", $request->date_range);
            $treasury_balances = $treasury_balances->whereDate('created_at', '>=', $date_range1[0]);
            $treasury_balances = $treasury_balances->whereDate('created_at', '<=', $date_range1[1]);
        }
        $treasury_balances_now = TreasuryBalanceHistory::all()->last();
        if (!empty($treasury_balances_now)) {
//     dd($treasury_balances_now->balance_after,$business_settings->value);
            if ($treasury_balances_now->balance_after < $business_settings->value) {
                $changed_treasury = $business_settings->value - $treasury_balances_now->balance_after;
            }
        }
        $treasury_balances = $treasury_balances->get();

        return view('backend.reports.treasury_balance_report', compact('changed_treasury', 'business_settings_initial', 'treasury_balances', 'date_range', 'business_settings'));
    }

    public function users_taam_report(Request $request)
    {
        $user_id = null;
        $date_range = null;

        $users = Staff::whereRoleId('3')->get();
        $business_settings_renewal = BusinessSetting::where('type', 'taam_expenses_renewal')->first();
        $business_settings_ownership = BusinessSetting::where('type', 'taam_expenses_ownership')->first();

//        $transactions = Transaction::orderBy('created_at', 'desc');
        $transaction_owner_count = Transaction::Where(function ($query) {
            $query->where('type', 1)
                ->orWhere('type', 3);
        });

        $transaction_renewal_count = Transaction::Where(function ($query) {
            $query->where('type', 2)
                ->orWhere('type', 3);
        });
        if ($request->has('user_id') && $request->user_id != "null") {
            $user_id = $request->user_id;
            $transaction_owner_count = $transaction_owner_count->where('user_id', $user_id);
            $transaction_renewal_count = $transaction_renewal_count->where('user_id', $user_id);

        }
        if ($request->date_range) {
            $date_range = $request->date_range;
            $date_range1 = explode(" / ", $request->date_range);
            $transaction_owner_count = $transaction_owner_count->whereDate('timedate', '>=', $date_range1[0])->whereDate('timedate', '<=', $date_range1[1]);

            $transaction_renewal_count = $transaction_renewal_count->whereDate('timedate', '>=', $date_range1[0])->whereDate('timedate', '<=', $date_range1[1]);
        }

        $transaction_owner_count = $transaction_owner_count->count();
        $transaction_renewal_count = $transaction_renewal_count->count();


        $total_ownership = 0;
        $total_renewal = 0;

        return view('backend.reports.users_taam_report', compact('date_range', 'users', 'business_settings_renewal', 'business_settings_ownership', 'user_id', 'total_renewal', 'total_ownership', 'transaction_renewal_count', 'transaction_owner_count'));
    }

    public function stock_report(Request $request)
    {
        $sort_by = null;
        $products = Product::orderBy('created_at', 'desc');
        if ($request->has('category_id')) {
            $sort_by = $request->category_id;
            $products = $products->where('category_id', $sort_by);
        }
        $products = $products->paginate(15);
        return view('backend.reports.stock_report', compact('products', 'sort_by'));
    }

    public function in_house_sale_report(Request $request)
    {
        $sort_by = null;
        $products = Product::orderBy('num_of_sale', 'desc')->where('added_by', 'admin');
        if ($request->has('category_id')) {
            $sort_by = $request->category_id;
            $products = $products->where('category_id', $sort_by);
        }
        $products = $products->paginate(15);
        return view('backend.reports.in_house_sale_report', compact('products', 'sort_by'));
    }

    public function seller_sale_report(Request $request)
    {
        $sort_by = null;
        $sellers = Seller::orderBy('created_at', 'desc');
        if ($request->has('verification_status')) {
            $sort_by = $request->verification_status;
            $sellers = $sellers->where('verification_status', $sort_by);
        }
        $sellers = $sellers->paginate(10);
        return view('backend.reports.seller_sale_report', compact('sellers', 'sort_by'));
    }

    public function wish_report(Request $request)
    {
        $sort_by = null;
        $products = Product::orderBy('created_at', 'desc');
        if ($request->has('category_id')) {
            $sort_by = $request->category_id;
            $products = $products->where('category_id', $sort_by);
        }
        $products = $products->paginate(10);
        return view('backend.reports.wish_report', compact('products', 'sort_by'));
    }

    public function user_search_report(Request $request)
    {
        $searches = Search::orderBy('count', 'desc')->paginate(10);
        return view('backend.reports.user_search_report', compact('searches'));
    }

    public function commission_history(Request $request)
    {
        $seller_id = null;
        $date_range = null;

        if (Auth::user()->user_type == 'seller') {
            $seller_id = $request->seller_id;
        }
        if ($request->seller_id) {
            $seller_id = $request->seller_id;
        }

        $commission_history = CommissionHistory::orderBy('created_at', 'desc');

        if ($request->date_range) {
            $date_range = $request->date_range;
            $date_range1 = explode(" / ", $request->date_range);
            $commission_history = $commission_history->where('created_at', '>=', $date_range1[0]);
            $commission_history = $commission_history->where('created_at', '<=', $date_range1[1]);
        }
        if ($seller_id) {

            $commission_history = $commission_history->where('seller_id', '=', $seller_id);
        }

        $commission_history = $commission_history->paginate(10);
        if (Auth::user()->user_type == 'seller') {
            return view('frontend.user.seller.reports.commission_history_report', compact('commission_history', 'seller_id', 'date_range'));
        }
        return view('backend.reports.commission_history_report', compact('commission_history', 'seller_id', 'date_range'));
    }

    public function send_mail_representative(Request $request)
    {


        if (env('MAIL_USERNAME') != null) {
            //sends newsletter to selected users
            $email = Representative::find($request->rep_id)->email;
            $array['view'] = 'emails.newsletter';
            $array['subject'] = 'report';
            $array['from'] = env('MAIL_USERNAME');
            $array['content'] = $request->content_page;

            try {
                Mail::to($email)->queue(new EmailManager($array));
                return 1;
            } catch (\Exception $e) {
                return 0;

            }

        }
    }
}
