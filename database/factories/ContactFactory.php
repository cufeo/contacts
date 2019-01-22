<?php

use Faker\Generator as Faker;

$factory->define(App\Contact::class, function (Faker $faker) {
    return [
        'email' => $faker->unique()->safeEmail,
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'phone' => $faker->unique()->phoneNumber,
        'birth_date' => $faker->dateTimeBetween($startDate = '-70 years', $endDate = '-23 years')->format('Y-m-d'),
        'function' => $faker->jobTitle,
        'city' => $faker->city,
        'company_name' => $faker->company,
        'company_address' => $faker->address,
    ];
});