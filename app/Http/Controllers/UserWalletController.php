<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteUserWalletRequest;
use App\Http\Requests\SaveUserWalletRequest;
use App\Http\Resources\UserWallets;
use App\UserWallet;
use Illuminate\Http\Request;
use App\Http\Resources\ResourceResponder;
use Illuminate\Support\Facades\Auth;

class UserWalletController extends Controller
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
     * Get wallets.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function get()
    {
        $wallets = UserWallet::where('user_id', Auth::id())->get();

        return $this->resource->send($wallets, UserWallets::class);
    }

    /**
     * Get wallet by id.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getById(int $id)
    {
        $wallet = UserWallet::where('user_id', Auth::id())->where('id', $id)->firstOrFail();

        return $this->resource->send($wallet, UserWallets::class);
    }

    /**
     * Create new wallet.
     *
     * @param SaveUserWalletRequest $request
     * @return mixed
     */
    public function store(SaveUserWalletRequest $request)
    {
        $request->merge(['user_id' => Auth::id()]);
        $wallet = UserWallet::create($request->all());

        return $this->resource->send($wallet, UserWallets::class);
    }

    /**
     * Edit wallet.
     *
     * @param SaveUserWalletRequest $request
     * @return mixed
     */
    public function edit(SaveUserWalletRequest $request)
    {
        $request->merge(['user_id' => Auth::id()]);
        $wallet = UserWallet::where('user_id', Auth::id())->where('id', $request->id)->firstOrFail();
        $wallet->update($request->all());

        return $this->resource->send($wallet, UserWallets::class);
    }

    /**
     * Delete wallet.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(int $id)
    {
        $wallet = UserWallet::where('user_id', Auth::id())->where('id', $id)->firstOrFail();
        $wallet->delete();
        return response()->json(['success' => true]);
    }
}
