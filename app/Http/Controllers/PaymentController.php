<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Braintree_Transaction;

use App\Gateway;

use App\Apartment;
use App\Payment;
use App\Tier;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
