<?php

namespace Database\Factories;

use App\Models\UserWallet;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserWalletFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UserWallet::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name'    => $this->faker->name,
            'type'    => UserWallet::TYPES['cash'],
            'user_id' => 1,
        ];
    }
}
