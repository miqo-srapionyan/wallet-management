<?php

namespace Database\Factories;

use App\Models\UserWalletBalance;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserWalletBalanceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UserWalletBalance::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'amount'    => 10,
            'type'      => UserWalletBalance::TYPES['credit'],
            'wallet_id' => 1
        ];
    }
}
