<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Product;
use App\User;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'description' => $faker->paragraph,
        'quantity' => $faker->numberBetween(1, 10),
        'status' => $faker->randomElement([
        	Product::AVAILABLE_PRODUCT,
        	Product::UNAVAILABLE_PRODUCT,
        ]),
        'image' => $faker->randomElement(['image1.jpeg', 'image2.jpeg', 'image3.jpeg']),
        'seller_id' => User::all()->random()->id
    ];
});
