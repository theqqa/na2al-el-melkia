<?php

namespace App\Models;

use App\Models\Expense;
use Illuminate\Database\Eloquent\Model;

class ExpenseTranslation extends Model
{
  protected $fillable = ['name', 'lang', 'expense_id'];

  public function expense(){
    return $this->belongsTo(Expense::class);
  }
}
