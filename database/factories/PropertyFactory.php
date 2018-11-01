<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Property::class, function (Faker $faker) {
    return [
        'street_address' => $faker->streetAddress,
        'city' => $faker->city,
        'post_code' => $faker->postcode,
        'country' => $faker->country,
        'longitude' => $faker->longitude,
        'latitude' => $faker->latitude
    ];
});
