<?php

namespace Tests\Http;

use App\User;
use App\UserWallet;
use App\UserWalletBalance;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserWalletBalanceTest extends TestCase
{

    use RefreshDatabase;

    private $uri = '/wallets/{id}/balance';

    /* GET */
    public function testGetUserWalletBalanceReturnErrorIfUserNotAuthenticated()
    {
        $wallet = factory(UserWallet::class)->create([
            'user_id' => $this->user->id
        ]);

        $response = $this->getJson(str_replace("{id}", $wallet->id, $this->uri));

        $response->assertStatus(401);
    }

    public function testGetUserWalletBalance()
    {
        $user = $this->user;
        $wallet = factory(UserWallet::class)->create([
            'user_id' => $user->id
        ]);
        $balances = factory(UserWalletBalance::class, 3)->create([
            'wallet_id' => $wallet->id
        ]);
        $response = $this->actingAs($user)->getJson(str_replace("{id}", $wallet->id, $this->uri));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            [
                'type',
                'amount',
            ]
        ]);
    }


    /* POST */
    public function testCreateNewWalletBalanceRecordReturnErrorIfUserNotAuthenticated()
    {
        $wallet = factory(UserWallet::class)->create([
            'user_id' => $this->user->id
        ]);
        $data = [
            'amount' => 15,
            'type' => UserWallet::TYPES['credit_card'],
        ];

        $response = $this->postJson(str_replace("{id}", $wallet->id, $this->uri), $data);

        $response->assertStatus(401);
    }

    public function testCreateNewWalletBalanceRecordReturnValidationError()
    {
        $wallet = factory(UserWallet::class)->create([
            'user_id' => $this->user->id
        ]);
        $data = [
            'amount' => '',
            'type' => '',
        ];

        $response = $this->actingAs($this->user)->postJson(str_replace("{id}", $wallet->id, $this->uri), $data);

        $response->assertStatus(422);

    }

    public function testCreateNewWalletBalanceRecordReturnValidationErrorNotExistingType()
    {
        $wallet = factory(UserWallet::class)->create([
            'user_id' => $this->user->id
        ]);
        $data = [
            'amount' => 10,
            'type' => 'custom_type',
        ];

        $response = $this->actingAs($this->user)->postJson(str_replace("{id}", $wallet->id, $this->uri), $data);

        $response->assertStatus(422);
    }


    public function testCreateNewWalletBalanceRecord()
    {
        $wallet = factory(UserWallet::class)->create([
            'user_id' => $this->user->id
        ]);
        $data = [
            'amount' => 15,
            'type' => UserWalletBalance::TYPES['credit'],
        ];

        $response = $this->actingAs($this->user)->postJson(str_replace("{id}", $wallet->id, $this->uri), $data);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'type',
            'amount',
        ]);
        $this->assertDatabaseHas((new UserWalletBalance())->getTable(), [
            'amount' => 15,
        ]);
    }


}
