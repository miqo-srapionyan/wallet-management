<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(\App\UserWalletBalance::class, function (Faker $faker, $wallet_id) {
    return [
        'amount' => 10,
        'type' => \App\UserWalletBalance::TYPES['credit'],
        'wallet_id' => $wallet_id
    ];
});
