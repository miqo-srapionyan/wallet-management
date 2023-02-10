<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserWallet extends Model
{
    use HasFactory;

    protected $table = 'user_wallets';

    protected $fillable = [
        'name',
        'type',
        'user_id',
        'total',
    ];

    const TYPES = [
        'cash' => 'cash',
        'credit_card' => 'credit_card'
    ];


    /** Relations **/

    public function user() {
        return $this->belongsTo(User::class);
    }

    /** End Relations **/
}
