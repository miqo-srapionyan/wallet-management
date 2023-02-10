<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserWalletBalance extends Model
{
    use HasFactory;

    protected $table = 'user_wallet_balances';

    protected $fillable = [
        'type',
        'wallet_id',
        'amount',
    ];

    const TYPES = [
        'credit' => 'credit',
        'debit' => 'debit'
    ];


    /** Relations **/

    public function wallet() {
        return $this->belongsTo(UserWallet::class);
    }

    /** End Relations **/
}
