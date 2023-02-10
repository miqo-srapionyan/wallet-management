<?php

namespace Tests\Http;

use App\Models\UserWallet;
use App\Models\UserWalletBalance;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserWalletBalanceTest extends TestCase
{
    use RefreshDatabase;

    private $uri = '/wallets/{id}/balance';

    public function testGetUserWalletBalanceReturnErrorIfUserNotAuthenticated()
    {
        $wallet = UserWallet::factory()->create([
            'user_id' => $this->user->id
        ]);

        $response = $this->getJson(str_replace("{id}", $wallet->id, $this->uri));

        $response->assertStatus(401);
    }

    public function testGetUserWalletBalance()
    {
        $user = $this->user;
        $wallet = UserWallet::factory()->create([
            'user_id' => $user->id
        ]);
        $balances = UserWalletBalance::factory(3)->create([
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

    public function testCreateNewWalletBalanceRecordReturnErrorIfUserNotAuthenticated()
    {
        $wallet = UserWallet::factory()->create([
            'user_id' => $this->user->id
        ]);

        $data = [
            'amount' => 15,
            'type'   => UserWallet::TYPES['credit_card'],
        ];

        $response = $this->postJson(str_replace("{id}", $wallet->id, $this->uri), $data);

        $response->assertStatus(401);
    }

    public function testCreateNewWalletBalanceRecordReturnValidationError()
    {
        $wallet = UserWallet::factory()->create([
            'user_id' => $this->user->id
        ]);

        $data = [
            'amount' => '',
            'type'   => '',
        ];

        $response = $this->actingAs($this->user)->postJson(str_replace("{id}", $wallet->id, $this->uri), $data);

        $response->assertStatus(422);
    }

    public function testCreateNewWalletBalanceRecordReturnValidationErrorNotExistingType()
    {
        $wallet = UserWallet::factory()->create([
            'user_id' => $this->user->id
        ]);

        $data = [
            'amount' => 10,
            'type'   => 'custom_type',
        ];

        $response = $this->actingAs($this->user)->postJson(str_replace("{id}", $wallet->id, $this->uri), $data);

        $response->assertStatus(422);
    }

    public function testCreateNewWalletBalanceRecord()
    {
        $wallet = UserWallet::factory()->create([
            'user_id' => $this->user->id
        ]);

        $data = [
            'amount' => 15,
            'type'   => UserWalletBalance::TYPES['credit'],
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
