<?php

use Illuminate\Database\Seeder;
use App\Feature;
use App\Apartment;


class FeaturesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Feature::class, 9)
        ->create()
        ->each(function($feat){

          $apts = Apartment::inRandomOrder()->take(rand(10,20))->get();

          $feat->apartments()->attach($apts);
        });
    }
}
