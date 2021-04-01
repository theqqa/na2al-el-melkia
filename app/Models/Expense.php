<?php

namespace App\Models;

;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\App;

/**
 * App\Models\Expense
 *
 * @property int $id
 * @property string $name

 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Expense newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Expense newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Expense query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Expense whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Expense whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Expense whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Expense whereUpdatedAt($value)
 * @mixin \Eloquent
 */

class Expense extends Model
{
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('alphabetical', function (Builder $builder) {
            $builder->orderBy('name', 'asc');
        });
    }
    public function expense_translations(){
        return $this->hasMany(ExpenseTranslation::class);
    }
    public function getTranslation($field = '', $lang = false){
        $lang = $lang == false ? App::getLocale() : $lang;
        $expense_translations = $this->hasMany(ExpenseTranslation::class)->where('lang', $lang)->first();
        return $expense_translations != null ? $expense_translations->$field : $this->$field;
    }

}
