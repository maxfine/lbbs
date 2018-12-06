<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Reply::class, function (Faker $faker) {
    $time = $faker->dateTimeThisMonth();
    return [
        'content' => $faker->text(),
        'created_at' => $time,
        'updated_at' => $time,
    ];
});
