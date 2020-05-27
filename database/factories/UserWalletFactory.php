<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(\App\UserWallet::class, function (Faker $faker, $user_id) {
    return [
        'name' => $faker->name,
        'type' => \App\UserWallet::TYPES['cash'],
        'user_id' => $user_id
    ];
});
