<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Apartment;
use App\Feature;
use App\Payment;
use App\Tier;

use Carbon\Carbon;

class IndexController extends Controller
{
    public function index(){

      $all_apts = Apartment::all()->each(function($apartment){

        $tier_id = $apartment -> tier_id;


        if ($tier_id > 1) {

          $payments = $apartment -> payments()->each(function($payment) use($apartment){


            $payment_date = Carbon::parse($payment -> created_at);
            $now = Carbon::now();

            $duration = Tier::findOrFail($apartment -> tier_id)-> duration;
            $expiration_date = Carbon::parse($payment -> created_at) -> addHour($duration);


            // SE la data di oggi è maggiore di quella di scadenza pagamento, bisogna aggiornare
            if ($now > $expiration_date) {

                  // il campo di tier_id nella tabella apartmnets, con 1
                  $apartment->update(['tier_id' => 1 ]);
                }

          });

        }

      });

      $apts = Apartment::where('tier_id', '>', '0')
                ->orderBy('tier_id', 'desc')
                -> take(9)
                -> get();
      $features = Feature::all();
      return view('welcome', compact('apts', 'features'));
    }
}
