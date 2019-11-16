<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;
use App\Tier;

$factory->define(Tier::class, function (Faker $faker) {


    return [

      'price',
      'level',
      'duration'

    ];
});
