<?php

// !! IMPORTANT !!
use Illuminate\Http\Request;
use App\Tier;
use App\Apartment;
use App\Payment;

Route::get('/', 'IndexController@index')->name('index');

Auth::routes();

//richiede autorizzazione
Route::get('/home', 'HomeController@index')->name('home');

Route::get('/users', 'UserController@index')->name('users.index');

Route::get('/search','ApartmentController@index')->name('apt.index');

Route::get('/apt/{id}', 'ApartmentController@show')
      ->name('apt.show');

Route::get('/new/apt', 'ApartmentController@create')
      ->name('apt.create')
      ->middleware('auth');

Route::post('/', 'ApartmentController@store')
      ->name('apt.store')
      ->middleware('auth');


Route::post('/message/create/{id}', 'MessageController@storeMessage')
      ->name('msg.guest.create');

Route::get('/apt/{id}/edit', 'ApartmentController@edit')
      ->name('apt.edit')
      ->middleware('auth');

Route::post('/{id}', 'ApartmentController@update')
      ->name('apt.update')
      ->middleware('auth');

Route::get('/apt/{id}/delete', 'ApartmentController@destroy')
      ->name('apt.destroy')
      ->middleware('auth');

Route::get('/index/{id}', 'PaymentController@index')
      ->name('payment.index')
      ->middleware('auth');

Route::get('/payment/{id}', 'PaymentController@create')
      ->name('payment.send')
      ->middleware('auth');

Route::get('/tiers/{id}', 'PaymentController@showTiers')
      ->name('tiers.get')
      ->middleware('auth');


Route::post('/map/{id}', 'SearchController@getMapFront')
      ->name('map.get');


Route::post('/payment/{id}', function(Request $request, $id){




    $gateway = new Braintree\Gateway([
          'environment' => env('BRAINTREE_ENV', 'sandbox'),
          'merchantId' => env('BRAINTREE_MERCHANT_ID'),
          'publicKey' => env('BRAINTREE_PUBLIC_KEY'),
          'privateKey' => env('BRAINTREE_PRIVATE_KEY'),
      ]);
    //
    // Id pagamento
    $nonceFromTheClient = $request-> payment_method_nonce;
    //
    // Trovo il Piano scelto dall'utente
     $tier = Tier::findOrFail($request['tier_id']);

     $amount = $tier -> price;

    //
    // TRANSAZIONE
    $result = $gateway->transaction()->sale([
      'amount' => $amount,
      'paymentMethodNonce' => $nonceFromTheClient,
      'customer' => [
        'firstName' => 'Tony',  //POSSONO ESSERE RIPORTATI I DATI DI CHI EFFETTUA IL PAGAMENTO
        'lastName' => 'Seppia',
        'email' => 'tony.seppia@gmail.com'
      ],
      'options' => [
        'submitForSettlement' => True
      ]
    ]);


    // IN CASO DI SUCCESSO DEL PAGAMENTO
    // if ($result->success) {

      $transaction = $result->transaction;

      // TODO: TRAMITE ARRAY RESULT posso estrapolare varie informazioni sulla transazione e salvarle nel database,

      // -------------------------------------------------------------------------------------------  //
      #  SALVATAGGIO DEL PAGAMENTO E DEL PIANO DI SOTTOSCRIZIONE NEL DATABASE                         #
      // ------------------------------------------------------------------------------------------- //

      // Validate data da oggetto request form
      $validatedData = $request->validate([

        "tier_id" => 'required'
      ]);

      // UPDATE tier_id, piano sottoscritto dall utente per l'appartamento, in tabella apartments
      $apt_id = $request-> apartment_id;
      $apartment = Apartment::findOrFail($apt_id);


      $tier_id = $validatedData['tier_id'];

      $apartment->update(["tier_id" => $tier_id]);


      // CREATE nuovo pagamento registrato nel database
      $newPay = Payment::create(['apartment_id' => $apt_id]);

      // -------------------------------------------------------------------------------------------  //
      #    FINE SALVATAGGIO                                                                           #
      // ------------------------------------------------------------------------------------------- //


      return response()->json([
        'success_message' => 'Transazione avvenuta con successo.',
        'ID transazione:' =>  $transaction-> id,
        'res' => $nonceFromTheClient,
        'tierid' => $request['tier_id'],
        'tier' => $tier,
        // 'price' => $amount,
        'validateddata' => $validatedData,
        'apartment' => $apartment,
        'gateway' => $gateway,

        'transaction result' => $result,
        'new_pay' => $newPay,


        $request->all()
      ]);

    // IN CASO DI NON SUCCESSO DELLA TRANSAZIONE
    // } else {
        // $errorString = "";
        // foreach ($result->errors->deepAll() as $error) {
            // $errorString .= 'Error: ' . $error->code . ": " . $error->message . "\n";
        // }
//
        // return response()->json([$request->all()]);
    // }

})->name('payment.send')->middleware('auth');
