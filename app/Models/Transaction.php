<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Transaction
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon $timedate
 * @property string|null $transaction_id
 * @property string|null $sub_representative
 * @property int|null $approved

 * @property int|null $representative_id
 * @property int|null $user_id
 * @property int $type
  * @property string|null $notes

 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Transaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Transaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Transaction query()
 *  * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Transaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Transaction whereId($value)

 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Transaction whereRepresentativeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Transaction whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Transaction extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function representative() {
        return $this->belongsTo(Representative::class);
    }
}
