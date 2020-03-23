<?php
/** @var Factory $factory */


use App\Models\Customer;
use Faker\Generator as Faker;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factory;

$factory->define(
    Customer::class, function (Faker $faker) {
        return [
            'first_name' => $faker->firstNameMale,
            'last_name' => $faker->lastName,
            'town_name' => Str::title($faker->word),
            'gender_id' => mt_rand(1, 100),
            'is_deleted' => mt_rand(0, 1),
        ];
    }
);
