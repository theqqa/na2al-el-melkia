<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\Integer;

/**
 * App\Models\Representative
 *
 * @property int $id
 * @property string $name
 * @property string $code
 * @property string $transfer_price
 * @property string $email
 * @property string $renewal_price
 * @property string $deserved_amount
 * @property string $initial_balance
 * @property Integer $active
 * @property \Illuminate\Support\Carbon $register_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Representative newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Representative newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Representative query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Representative whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Representative whereId($value)
// * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Representative wherePhoto($value)
// * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Representative wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Representative whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Representative whereUpdatedAt($value)
 * @mixin \Eloquent
 */

class Representative extends Model
{
    protected static function boot()
    {
        parent::boot();

//        static::addGlobalScope('active', function (Builder $builder) {
//            $builder->where('active', 1);
//        });
    }
}
