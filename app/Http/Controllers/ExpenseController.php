<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseTranslation;
use App\Models\PermissionExchange;
use Illuminate\Http\Request;
use App\Brand;
use App\BrandTranslation;
use App\Product;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort_search =null;
        $expenses = Expense::orderBy('name', 'asc');
        if ($request->has('search')){
            $sort_search = $request->search;
            $expenses = $expenses->where('name', 'like', '%'.$sort_search.'%');
        }
        $expenses = $expenses->paginate(15);
        return view('backend.expense.index', compact('expenses', 'sort_search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.expense.create');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        $expense = new Expense();
        $expense->name = $request->name;
        $expense->initial_balance = $request->initial_balance;
        $expense->register_at = $request->register_at;


        $expense->save();

        $expense_translation = ExpenseTranslation::firstOrNew(['lang' => env('DEFAULT_LANGUAGE'), 'expense_id' => $expense->id]);
        $expense_translation->name = $request->name;
        $expense_translation->save();

        flash(translate('Expense Item has been inserted successfully'))->success();
        return redirect()->route('expenses.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $expense  = Expense::findOrFail($id);
        return view('backend.expense.show', compact('expense'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $lang   = $request->lang;
        $expense  = Expense::findOrFail($id);
        return view('backend.expense.edit', compact('expense','lang'));
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

        $expense = Expense::findOrFail($id);
        if($request->lang == env("DEFAULT_LANGUAGE")){
            $expense->name = $request->name;
        }
        $expense->initial_balance = $request->initial_balance;
        $expense->register_at = $request->register_at;
        $expense->save();

        $expense_translation = ExpenseTranslation::firstOrNew(['lang' => $request->lang, 'expense_id' => $expense->id]);
        $expense_translation->name = $request->name;
        $expense_translation->save();

        flash(translate('Expense Item has been updated successfully'))->success();
        return redirect()->route('expenses.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $expense = Expense::findOrFail($id);
//        Product::where('brand_id', $brand->id)->delete();
      $per_count=  PermissionExchange::whereExpenseId($id)->count();
        if($per_count>0 )
        {
            flash(translate('This Expense have Related Permission Exchange. Can\'t deleted'))->error();
            return back();
        }
        foreach ($expense->expense_translations as $key => $expense_translation) {
            $expense_translation->delete();
        }
        Expense::destroy($id);

        flash(translate('Expense Item has been deleted successfully'))->success();
        return redirect()->route('expenses.index');

    }
}
