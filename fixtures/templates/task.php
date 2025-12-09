<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */
return [
    'date_add' => $faker->dateTimeThisMonth()->format('Y-m-d H:i:s'),
    'title' => $faker->word(),
    'description' => $faker->sentence,
    'category_id' => $faker->randomDigit(),
    'location' => $faker->randomDigit(),
    'budget' => $faker->randomNumber(3, true),
    'deadline' => $faker->dateTimeThisMonth('+50 days')->format('Y-m-d H:i:s'),
    'status' => $faker->lexify(),
    'city_id' => $faker->randomDigit(),
    'customer_id' => $faker->randomDigit(),
    'performer_id' => $faker->randomDigit(),

];
