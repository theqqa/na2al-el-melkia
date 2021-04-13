<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\TreasuryBalanceHistory
 *
 * @property int $id
 * @property int|null $catch_receipt_id
 * @property int|null $permission_exchange_id
 * @property string $balance_before
 * @property string $balance_request
 * @property string $balance_after
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TreasuryBalanceHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TreasuryBalanceHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TreasuryBalanceHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TreasuryBalanceHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\TreasuryBalanceHistory whereUpdatedAt($value)
 * @mixin \Eloquent
 */

class TreasuryBalanceHistory extends Model
{
    protected $guarded = [];

    public function catchReceipt()
    {
        return $this->belongsTo(CatchReceipt::class);
    }
    public function permissionExchange()
    {
        return $this->belongsTo(PermissionExchange::class);
    }

}
