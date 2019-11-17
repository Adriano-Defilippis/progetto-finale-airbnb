<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Braintree_Transaction;

use App\Gateway;

use App\Apartment;
use App\Payment;
use App\Tier;

use Carbon\Carbon;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $apartment = Apartment::findOrFail($id);

        $tier_id = $apartment -> tier_id;

        $id_tier_active= Tier::findOrFail($apartment -> tier_id);

        $payment = $apartment -> payments()->orderBy('created_at', 'asc')->first();

          if($payment){
            $duration =  $id_tier_active -> duration;
            $last_payment =  Carbon::parse($payment -> created_at);

            $expiration_date = Carbon::parse($payment -> created_at) -> addHour($duration);
            $now = Carbon::now();

            $msg = "Expiration Time -" . $expiration_date->diffInHours($now,true) . " Hours";


            $diff= "Nessun pagamento ancora effettuato";
            if ($last_payment) {

              // Differenza in giorni dall'ultimo pagamento
              $diff = $last_payment->diffInHours($now,true) . " Hours ago";

              if ($diff > 24) {

                $diff = $last_payment->diffInDays($now,true) . " Days ago";
              }
            }

            if ($now > $expiration_date) {

              $msg = "Sottoscozione scaduta";

              $diff = $expiration_date->diffInHours($now,true);
            }

            return response()->json([

              "last_payment" => $last_payment,
              "expiration_date" => $expiration_date,
              $duration,
              $now,
              "msg_subs" => $msg,
              "diff" => $diff,
              "tier_id_from_apt" => $tier_id
            ]);
          }
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {


      $apt=  Apartment::findOrFail($id);

       // PER TEST, TOKEN STATICO ACCOUNT BRAINTREE
       $token = "sandbox_7bgcfdq8_hstckbs9tty2wg8q";
       //
       return response()->json([
         "token-braintree"=> $token,
         "message"=> 'ciao'
         // "tiers"=> $tiers
       ]);


      // // Crea nuvo oggetto classe Braintree/gateway
      // $gateway = new Braintree\Gateway([
      //       'environment' => config('services.braintree.environment'),
      //       'merchantId' => config('services.braintree.merchantId'),
      //       'publicKey' => config('services.braintree.publicKey'),
      //       'privateKey' => config('services.braintree.privateKey')
      //   ]);
      //
      //   PER TEST, TOKEN STATICO ACCOUNT BRAINTREE
      //   $token = "sandbox_7bgcfdq8_hstckbs9tty2wg8q";
      //
      //   $apt = Apartment::findOrFail($id);
      //
      //   return view('aptshow', compact('apt'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // $validatedData = $request->validate([
        //
        //   "tier_id" => 'required'
        // ]);

        // dd($validatedData, $id);
        dd('ciao');

        // $tier = Tier::findOrFail($validatedData['tier_id']);
        //
        //
        //
        //
        // dd($tier, $validatedData['tier_id'], $tier -> price, $tier -> level, $tier -> duration, $request);
        //
        //
        // dd($request);
        //
        //
        // // REGISTRAZIONE PAGAMENTO E UPDATE iter_id in apartments
        // $apartment = Apartment::findOrFail($id);
        //
        // if ($apartment) {
        //
        //     $tier_id = $validatedData['tier_id'];
        //
        //     $apartment->update(["tier_id" => $tier_id]);
        //
        // }
        //
        // $newPay = Payment::create(['apartment_id' => $id]);
        //
        // dd($newPay);
        //
        // return back()
        //   ->with('success_message', 'Transazione avvenuta con successo. ID transazione:' . $tansaction -> id);


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    public function showTiers()
    {
        $tiers = Tier::where('id', '>', 1)->select('id', 'price', 'level', 'duration')->get();

        return response()->json([

          "tiers" => $tiers
        ]);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
