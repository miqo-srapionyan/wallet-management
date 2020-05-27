<?php

namespace App\Http\Resources;

use App\UserWallet;
use Illuminate\Http\Resources\Json\JsonResource;

class UserWalletBalances extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'wallet' => $this->wallet,
            'amount' => $this->amount,
            'type' => $this->type,
        ];
    }
}
