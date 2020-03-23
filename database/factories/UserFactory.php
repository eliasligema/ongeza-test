<?php
/** @var Factory $factory */


use App\Models\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factory;

$factory->define(
    User::class, function (Faker $faker) {
        return [
            'first_name' => $faker->firstNameMale,
            'second_name' => $faker->lastName,
            'last_name' => $faker->lastName,
            'username' => Str::title($faker->word),
            'email' => $faker->unique()->email,
            'phone' => '255'.mt_rand(754, 756).mt_rand(100000, 999999),
            'profile_url' => $faker->word,
            'password' => bcrypt('12345678'),
            'role' => mt_rand(0, 3),
            'remember_token' => $faker->word,
            'status' => mt_rand(0, 3),
            'created_at' => $faker->dateTimeBetween('-3 years', 'now'),
            'updated_at' => $faker->dateTimeBetween('-3 years', 'now'),
            'deleted_at' => null,
        ];
    }
);
