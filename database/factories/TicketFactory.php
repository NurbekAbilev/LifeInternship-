<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Ticket::class, function (Faker $faker) {
    return [
        'full_name' => $faker->name,
        'email' => $faker->email,
        'phone_num' => $faker->tollFreePhoneNumber,
        'hash' => md5(str_random()),
        'description' => $faker->text,
        'created_at' => now(),
        'updated_at' => now()
    ];
});
