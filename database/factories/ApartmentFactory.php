<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;
use App\Apartment;

$factory->define(Apartment::class, function (Faker $faker) {
    return [
      'description'=>$faker-> text(1000) ,
      'rooms'=>rand(1, 5) ,
      'beds'=>rand(1,10) ,
      'bathrooms'=>rand(1,3) ,
      'mq'=>rand(40, 200) ,
      'address'=> $faker-> address,
      'visualizations'=>rand(10, 1000) ,
      'active'=>$faker-> boolean ,
      'lat'=> $faker-> latitude,
      'lon'=> $faker-> longitude
    ];
});
