<?php
/** @var Factory $factory */


use App\Models\Gender;
use Faker\Generator as Faker;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factory;

$factory->define(
    Gender::class, function (Faker $faker) {
        return [
            'gender_name' => Str::title($faker->word),
        ];
    }
);
