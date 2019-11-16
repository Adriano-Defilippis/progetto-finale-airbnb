<?php

use Illuminate\Database\Seeder;
use App\Apartment;
use App\User;
use App\Tier;
use App\Feature;

class ApartmentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        factory(Apartment::class, 9)
          -> make()
          -> each(function($apartment){
             //Aggiungo valori per chiave estern user_id
             $user = User::inRandomOrder() -> first();
             $apartment -> user() -> associate($user);

             //Aggiungo valori per chiave esterna tier_id
             // $tier = Tier::inRandomOrder() -> first();
             // $apartment -> tier() -> associate($tier);

             $apartment -> save();
    });
  }
}
