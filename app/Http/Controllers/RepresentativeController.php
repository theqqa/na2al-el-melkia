<?php

namespace App\Http\Controllers;

use App\Models\CatchReceipt;
use App\Models\Representative;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Seller;
use App\User;
use App\Shop;
use App\Product;
use App\Order;
use App\OrderDetail;
use Illuminate\Support\Facades\Hash;
use App\Notifications\EmailVerificationNotification;
use Illuminate\Support\Facades\Validator;

class RepresentativeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort_search = null;
        $active_status = null;
        $representatives = Representative::orderBy('created_at', 'desc');
        if ($request->has('search')){
            $sort_search = $request->search;
            $representatives=       $representatives->where('name', 'like', '%'.$sort_search.'%');

        }
        if ($request->active_status != null) {
            $active_status = $request->active_status;
            $representatives = $representatives->where('active', $active_status);
        }
        $representatives = $representatives->paginate(15);
        return view('backend.representatives.index', compact('representatives', 'sort_search', 'active_status'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.representatives.create');
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
            'name' => 'required|unique:representatives|max:255',
        ])->validate();
        $user = new Representative;
        $user->name = $request->name;

        $user->transfer_price = $request->transfer_price;
        $user->renewal_price =  $request->renewal_price;
        $user->deserved_amount = $request->initial_balance;
        $user->email = $request->email;
        $user->code = $request->code;
        $user->initial_balance = $request->initial_balance;
        $user->register_at = $request->register_at;

        if($user->save()){
            $user->code = 'Re-'.$user->id;
            $user->save();
                flash(translate('Representative has been inserted successfully'))->success();
                return redirect()->route('representatives.index');

        }
        flash(translate('Something went wrong'))->error();
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $representative = Representative::findOrFail(decrypt($id));
        return view('backend.representatives.show', compact('representative'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $representative = Representative::findOrFail(decrypt($id));
        return view('backend.representatives.edit', compact('representative'));
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
                'name' => 'required|unique:representatives,name,'.$id,
            ])->validate();
        $user = Representative::findOrFail($id);
        $rep_trans=Transaction::whereRepresentativeId($id)->count();
        $rep_catch=CatchReceipt::whereRepresentativeId($id)->count();
        if($rep_trans>0 or $rep_catch >0)
        {
            flash(translate('This representative have Related transactions or Catch Receipts . Can\'t deleted'))->error();
            return redirect()->route('representatives.index');
        }
            $user->name = $request->name;
            $user->transfer_price = $request->transfer_price;
            $user->renewal_price = $request->renewal_price;
            $user->deserved_amount = $request->initial_balance;

            $user->email = $request->email;
            $user->code = $request->code;
            $user->initial_balance = $request->initial_balance;
            $user->register_at = $request->register_at;
            if ($user->save()) {

                flash(translate('Representative has been updated successfully'))->success();
                return redirect()->route('representatives.index');

            }

        flash(translate('Something went wrong'))->error();
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $rep = Representative::findOrFail($id);
//        Shop::where('user_id', $seller->user_id)->delete();
//        Product::where('user_id', $seller->user_id)->delete();
//        Order::where('user_id', $seller->user_id)->delete();
//        OrderDetail::where('seller_id', $seller->user_id)->delete();
//        User::destroy($seller->user->id);
$rep_trans=Transaction::whereRepresentativeId($id)->count();
$rep_catch=CatchReceipt::whereRepresentativeId($id)->count();
    if($rep_trans>0 or $rep_catch >0)
    {
        flash(translate('This representative have Related transactions or or Catch Receipts. Can\'t deleted'))->error();
        return back();
    }
        if(Representative::destroy($id)){
            flash(translate('Representative has been deleted successfully'))->success();
            return redirect()->route('representatives.index');
        }
        else {
            flash(translate('Something went wrong'))->error();
            return back();
        }
    }








    public function updateActive(Request $request)
    {
        $representative = Representative::findOrFail($request->id);
        $representative->active = $request->status;
        if($representative->save()){
            return 1;
        }
        return 0;
    }




}
