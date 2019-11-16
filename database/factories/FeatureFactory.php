<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;
use App\Feature;

$factory->define(Feature::class, function (Faker $faker) {

   $featuresList = [
      'Wi-Fi',
      'Sauna',
      'Jacuzzi',
      'Vista Mare',
      'Terrazzo',
      'Parcheggio',
      'Portineria',
      'Rampe Disabili',
      'Servizio Navetta'
    ];
    return [
        'type'=> $faker-> unique()-> randomElement($featuresList)
    ];
});
