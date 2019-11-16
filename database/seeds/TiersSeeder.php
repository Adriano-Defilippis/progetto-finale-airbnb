<?php

use Illuminate\Database\Seeder;
use App\Tier;

class TiersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

      $duration = [
        null,
        24,
        72,
        144
      ];

      $level = [
        0,
        1,
        2,
        3
      ];

      $price = [
        null,
        2.99,
        5.99,
        9.99
      ];

      $sub = [];

      for ($i=0; $i < 4; $i++) {

        $sub[$i] = [
          'price' => $price[$i],
          'level' => $level[$i] ,
          'duration' => $duration[$i]
          
          ];

        Tier::create($sub[$i]);
      }


    }
}
