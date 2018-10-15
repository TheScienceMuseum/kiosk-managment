<?php

use Faker\Generator as Faker;

$factory->define(App\Kiosk::class, function (Faker $faker) {
    return [
        'name' => implode(' ', $faker->unique()->words),
        'client_version' => '0.0.1',
        'location' => $faker->city,
        'asset_tag' => $faker->unique()->uuid,
        'identifier' => $faker->unique()->uuid,
        'last_seen_at' => now(),
    ];
});
