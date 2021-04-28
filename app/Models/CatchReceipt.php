<?php

namespace App\Models;

use App\Category;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CatcheRceipt
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon $date
 * @property string|null $price
 * @property int|null $representative_id
 * @property int|null $code
 * @property int $approved
 * @property string|null $payment_by
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CatchReceipt newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CatchReceipt newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CatchReceipt query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CatchReceipt whereRepresentativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CatchReceipt whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CatchReceipt whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CatchReceipt extends Model
{
    public function representative() {
        return $this->belongsTo(Representative::class);
    }
}
