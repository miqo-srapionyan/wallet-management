<?php

namespace Tests\Http;

use App\Models\User;
use App\Models\UserWallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserWalletTest extends TestCase
{
    use RefreshDatabase;

    private $uri = '/wallets';

    public function testGetUserWalletsReturnErrorIfUserNotAuthenticated()
    {
        $response = $this->getJson($this->uri);

        $response->assertStatus(401);
    }

    public function testGetUserWallets()
    {
        $user = $this->user;
        $wallets = UserWallet::factory(3)->create([
            'user_id' => $user->id
        ]);
        $response = $this->actingAs($user)->getJson($this->uri);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            [
                'name',
                'type',
                'total',
            ]
        ]);
    }

    public function testGetUserWalletByIdReturnErrorIfNotExists()
    {
        $response = $this->actingAs($this->user)->getJson($this->uri."/0");
        $response->assertStatus(404);
    }

    public function testGetUserWalletByIdReturnErrorIfNotBelongsToCurrentUser()
    {
        UserWallet::factory()->create([
            'user_id' => $this->user->id
        ]);
        $otherUser = User::factory()->create();
        $wallet = UserWallet::factory()->create([
            'user_id' => $otherUser->id
        ]);

        $response = $this->actingAs($this->user)->getJson($this->uri."/".$wallet->id);

        $response->assertStatus(404);
    }

    public function testGetUserWalletById()
    {
        $wallet = UserWallet::factory()->create([
            'user_id' => $this->user->id
        ]);

        $response = $this->actingAs($this->user)->getJson($this->uri."/".$wallet->id);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'name',
            'type',
            'total',
        ]);
    }

    public function testCreateNewWalletReturnErrorIfUserNotAuthenticated()
    {
        $data = [
            'name'    => 'New Wallet',
            'type'    => UserWallet::TYPES['credit_card'],
            'user_id' => $this->user->id
        ];

        $response = $this->postJson($this->uri, $data);

        $response->assertStatus(401);
    }

    public function testCreateNewWalletReturnValidationError()
    {
        $data = [
            'name'    => '',
            'type'    => '',
            'user_id' => $this->user->id
        ];

        $response = $this->actingAs($this->user)->postJson($this->uri, $data);

        $response->assertStatus(422);
    }

    public function testCreateNewWalletReturnValidationErrorNotExistingType()
    {
        $data = [
            'name'    => 'New Wallet',
            'type'    => 'custom_type',
            'user_id' => $this->user->id
        ];

        $response = $this->actingAs($this->user)->postJson($this->uri, $data);

        $response->assertStatus(422);
    }

    public function testCreateNewWallet()
    {
        $data = [
            'name'    => 'New Wallet',
            'type'    => UserWallet::TYPES['credit_card'],
            'user_id' => $this->user->id
        ];

        $response = $this->actingAs($this->user)->postJson($this->uri, $data);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'name',
            'type',
            'total',
        ]);
        $this->assertDatabaseHas((new UserWallet())->getTable(), [
            'name' => 'New Wallet',
        ]);
    }

    public function testUpdateWalletReturnErrorIfUserNotAuthenticated()
    {
        $data = [
            'name'    => 'New Wallet',
            'type'    => UserWallet::TYPES['credit_card'],
            'user_id' => $this->user->id
        ];

        $wallet = UserWallet::factory()->create([
            'user_id' => $this->user->id
        ]);

        $response = $this->patchJson($this->uri."/".$wallet->id, $data);

        $response->assertStatus(401);
    }

    public function testUpdateWalletReturnValidationError()
    {
        $data = [
            'name'    => '',
            'type'    => '',
            'user_id' => $this->user->id
        ];
        $wallet = UserWallet::factory()->create([
            'user_id' => $this->user->id
        ]);
        $response = $this->actingAs($this->user)->patchJson($this->uri."/".$wallet->id, $data);

        $response->assertStatus(422);
    }

    public function testUpdateWalletReturnValidationErrorNotExistingType()
    {
        $data = [
            'name'    => 'New Wallet',
            'type'    => 'custom_type',
            'user_id' => $this->user->id
        ];
        $wallet = UserWallet::factory()->create([
            'user_id' => $this->user->id
        ]);
        $response = $this->actingAs($this->user)->patchJson($this->uri."/".$wallet->id, $data);

        $response->assertStatus(422);
    }

    public function testUpdateWallet()
    {
        $data = [
            'name'    => 'New Wallet123',
            'type'    => UserWallet::TYPES['credit_card'],
            'user_id' => $this->user->id
        ];
        $wallet = UserWallet::factory()->create([
            'user_id' => $this->user->id
        ]);
        $response = $this->actingAs($this->user)->patchJson($this->uri."/".$wallet->id, $data);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'name',
            'type',
            'total',
        ]);
        $this->assertDatabaseHas((new UserWallet())->getTable(), [
            'name' => 'New Wallet123',
        ]);
    }

    public function testDeleteWalletReturnErrorIfUserNotAuthenticated()
    {
        $wallet = UserWallet::factory()->create([
            'name'    => 'Custom Wallet',
            'user_id' => $this->user->id
        ]);
        $response = $this->deleteJson($this->uri."/".$wallet->id);

        $response->assertStatus(401);
    }

    public function testDeleteWalletReturnErrorIfNotExistsWallet()
    {
        $wallet = UserWallet::factory()->create([
            'name'    => 'Custom Wallet',
            'user_id' => $this->user->id
        ]);
        $response = $this->actingAs($this->user)->deleteJson($this->uri."/0");

        $response->assertStatus(404);
    }

    public function testDeleteWalletReturnErrorIfWalletNotBelongsCurrentUser()
    {
        $otheruser = User::factory()->create();
        $wallet = UserWallet::factory()->create([
            'name'    => 'Custom Wallet',
            'user_id' => $otheruser->id
        ]);
        UserWallet::factory()->create([
            'name'    => 'Custom Wallet',
            'user_id' => $this->user->id
        ]);
        $response = $this->actingAs($this->user)->deleteJson($this->uri."/".$wallet->id);

        $response->assertStatus(404);
    }

    public function testDeleteWallet()
    {
        $wallet = UserWallet::factory()->create([
            'name'    => 'Custom Wallet',
            'user_id' => $this->user->id
        ]);
        $response = $this->actingAs($this->user)->deleteJson($this->uri."/".$wallet->id);

        $response->assertStatus(200);
        $this->assertDatabaseMissing((new UserWallet())->getTable(), [
            'name' => 'Custom Wallet',
        ]);
    }

}
