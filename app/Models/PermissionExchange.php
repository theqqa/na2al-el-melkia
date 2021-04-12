<?php

namespace App\Models;

;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\App;

/**
 * App\Models\PermissionExchange
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon $date
 * @property string $price
 * @property int $expense_id
 * @property int $approved
 * @property int $status
 * @property string $expense_by
 * @property string $description
 * @property \Illuminate\Support\Carbon $exchange_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon $updated_at


 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PermissionExchange newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PermissionExchange newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PermissionExchange query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PermissionExchange whereExpenseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PermissionExchange whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PermissionExchange whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PermissionExchange whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PermissionExchange whereApproved($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PermissionExchange whereUpdatedAt($value)
 * @mixin \Eloquent
 */

class PermissionExchange extends Model
{
    protected static function boot()
    {
        parent::boot();


    }
    public function expense()
    {
        return $this->belongsTo(Expense::class);
    }

}
