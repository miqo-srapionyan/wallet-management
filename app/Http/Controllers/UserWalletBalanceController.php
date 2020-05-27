<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveUserWalletBalanceRequest;
use App\Http\Resources\ResourceResponder;
use App\Http\Resources\UserWalletBalances;
use App\UserWallet;
use App\UserWalletBalance;
use Illuminate\Support\Facades\Auth;

class UserWalletBalanceController extends Controller
{
    protected $resource;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ResourceResponder $resource)
    {
        $this->resource = $resource;
    }

    /**
     * Get wallet balances.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function get(int $wallet_id)
    {
        // Check if user owns this wallet
        UserWallet::where('user_id', Auth::id())->where('id', $wallet_id)->firstOrFail();
        $balances = UserWalletBalance::with('wallet')->where('wallet_id', $wallet_id)->get();

        return $this->resource->send($balances, UserWalletBalances::class);
    }


    /**
     * Create new balance record.
     *
     * @param SaveUserWalletBalanceRequest $request
     * @return mixed
     */
    public function store(SaveUserWalletBalanceRequest $request, int $wallet_id)
    {
        // Check if user owns this wallet
        $request->merge(['wallet_id' => $wallet_id]);
        $wallet = UserWallet::where('user_id', Auth::id())->where('id', $wallet_id)->firstOrFail();
        $balance = UserWalletBalance::create($request->all());
        if($request->type === UserWalletBalance::TYPES['credit']){
            $wallet->increment('total', $request->amount);
        }else{
            $wallet->decrement('total', $request->amount);
        }
        $balance->load('wallet');

        return $this->resource->send($balance, UserWalletBalances::class);
    }


}
