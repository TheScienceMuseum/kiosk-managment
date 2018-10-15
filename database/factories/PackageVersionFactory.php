<?php

use Faker\Generator as Faker;

$factory->define(App\PackageVersion::class, function (Faker $faker) {
    return [
        'version' => 1,
    ];
});
