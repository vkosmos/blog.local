<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\Post::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence,
        'content' => $faker->sentence,
        'image' => 'image1.jpeg',
        'date' => '08/09/17',
        'views' => $faker->numberBetween(0, 5000),
        'category_id' => 5,
//        'tags' => [2,3,4,5,6],
        'user_id' => 1,
        'status' => 1,
        'is_featured' => 0,
    ];
});
