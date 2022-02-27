<?php

namespace App\Http\Controllers;

use App\Models\CatchReceipt;
use App\Models\Representative;
use App\Models\SubRepresentative;
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

class SubRepresentativeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort_search = null;
        $representatives = SubRepresentative::orderBy('created_at', 'desc');
        if ($request->has('search')){
            $sort_search = $request->search;
            $representatives =$representatives->where('name', 'like', '%'.$sort_search.'%');
        }

        $representatives = $representatives->paginate(15);
        return view('backend.sub_representatives.index', compact('representatives', 'sort_search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.sub_representatives.create');
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
            'name' => 'required|unique:sub_representatives|max:255',
        ])->validate();
        $user = new SubRepresentative;
        $user->name = $request->name;



        if($user->save()){
            $user->save();
                flash(translate('Sub Representative has been inserted successfully'))->success();
                return redirect()->route('sub_representatives.index');

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
//        $representative = Representative::findOrFail(decrypt($id));
//        return view('backend.representatives.show', compact('representative'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $representative = SubRepresentative::findOrFail(decrypt($id));
        return view('backend.sub_representatives.edit', compact('representative'));
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
                'name' => 'required|unique:sub_representatives,name,'.$id,
            ])->validate();
        $user = SubRepresentative::findOrFail($id);
            $user->name = $request->name;

            if ($user->save()) {

                flash(translate('Sub Representative has been updated successfully'))->success();
                return redirect()->route('sub_representatives.index');

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
        $rep = SubRepresentative::findOrFail($id);

$rep_trans=Transaction::whereSubRepresentative($id)->count();
    if($rep_trans>0 )
    {
        flash(translate('This Sub representative have Related transactions. Can\'t deleted'))->error();
        return back();
    }
        if(SubRepresentative::destroy($id)){
            flash(translate('SubRepresentative has been deleted successfully'))->success();
            return redirect()->route('sub_representatives.index');
        }
        else {
            flash(translate('Something went wrong'))->error();
            return back();
        }
    }



}
