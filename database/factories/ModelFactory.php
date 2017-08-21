<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'id' => function() {
            return factory(App\User::class)->create()->id;
        },
        'onboarding_percentage' => $faker->numberBetween(0, 100),
        'created_at' => $faker->dateTimeBetween('-2 months', 'now'),
    ];
});
