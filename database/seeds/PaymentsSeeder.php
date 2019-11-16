<?php

use Illuminate\Database\Seeder;
use App\Payment;
use App\Tier;
use App\Apartment;
use App\User;

class PaymentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Payment::class, 30)->make()
              ->each(function($payment){

                // Associo valore chiave esterna per tier_id
                // $tier = Tier::inRandomOrder()->first();
                // $payment -> tier() -> associate($tier);


                // Associo valore chiave esterna per apartment_id
                $apt = Apartment::inRandomOrder()->first();

                $payment -> apartment() -> associate($apt);


                // Associo valore chiave esterna user_id
                // $user = User::inRandomOrder()->first();
                // $payment -> user() -> associate($user);

                $payment -> save();
              });



        // for ($i=0; $i <30 ; $i++) {
        //
        //   $apt = Apartment::inRandomOrder()->first();
        //
        //   $payment = [
        //     'apartment_id' => $apt -> id
        //   ];
        //
        //   Payment::create($payment);
        // }


    }
}
