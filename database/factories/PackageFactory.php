<?php

use Faker\Generator as Faker;

$factory->define(App\Package::class, function (Faker $faker) {
    return [
        'name' => implode(' ', $faker->unique()->words(1)),
    ];
});
