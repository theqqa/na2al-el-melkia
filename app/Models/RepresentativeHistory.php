<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\Integer;

/**
 * App\Models\RepresentativeHistory
 *
 * @property int $id
  * @property int $rep_id
 * @property int $catch_receipt_id
 * @property string|null $transaction_id

 * @property string $deserved_amount_before
  * @property string $deserved_amount_after
 * @property string $deserved_amount_request

 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RepresentativeHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RepresentativeHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RepresentativeHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RepresentativeHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\RepresentativeHistory whereUpdatedAt($value)
 * @mixin \Eloquent
 */

class RepresentativeHistory extends Model
{
    protected static function boot()
    {
        parent::boot();

//        static::addGlobalScope('active', function (Builder $builder) {
//            $builder->where('active', 1);
//        });
    }
    public function representative() {
        return $this->belongsTo(Representative::class,'rep_id');
    }

    public function catchReceipt() {
        return $this->belongsTo(CatchReceipt::class,'catch_receipt_id');
    }

    public function transaction() {
        return $this->belongsTo(Transaction::class,'transaction_id');
    }
}
