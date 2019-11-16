<?php

// !! IMPORTANT !!
use Illuminate\Http\Request;
use App\Tier;

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

Route::get('/{id}', 'ApartmentController@destroy')
      ->name('apt.destroy')
      ->middleware('auth');

Route::post('/payment/store/{id}', 'PaymentController@make')
      ->name('payment.store')
      ->middleware('auth');

Route::post('/payment/store/{id}', 'PaymentController@store')
      ->name('store.payment')
      ->middleware('auth');


Route::post('/payment/{id}', function(Request $request, $id){


    $gateway = new Braintree\Gateway([
          'environment' => env('BRAINTREE_ENV', 'sandbox'),
          'merchantId' => env('BRAINTREE_MERCHANT_ID'),
          'publicKey' => env('BRAINTREE_PUBLIC_KEY'),
          'privateKey' => env('BRAINTREE_PRIVATE_KEY'),
      ]);

    // Id pagamento
    $nonceFromTheClient = $request-> payment_method_nonce;

    // Trovo il Piano scelto dall'utente
    $tier = Tier::findOrFail($request['tier_id']);

    $amount = $tier -> price;

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
    if ($result->success) {

      $transaction = $result->transaction;

      // TODO: TRAMITE ARRAY RESULT posso estrapolare varie informazioni sulla transazione e salvarle nel database,

      //ad esempio posso salvare l'id della transazione e metterlo nel database per tracciabilità o memorizzare i dati
      //per pagamenti successivi

      // -------------------------------------------------------------------------------------------  //
      #  SALVATAGGIO DEL PAGAMENTO E DEL PIANO DI SOTTOSCRIZIONE NEL DATABASE                         #
      // ------------------------------------------------------------------------------------------- //

      // Validate data da oggetto request form
      $validatedData = $request->validate([

        "tier_id" => 'required'
      ]);


      // UPDATE tier_id, piano sottoscritto dall utente per l'appartamento, in tabella apartments
      $apartment = App\Apartment::findOrFail($id);

      if ($apartment) {

          $tier_id = $validatedData['tier_id'];

          $apartment->update(["tier_id" => $tier_id]);

      }

      // CREATE nuovo pagamento registrato nel database
      $newPay = App\Payment::create(['apartment_id' => $id]);

      // -------------------------------------------------------------------------------------------  //
      #    FINE SALVATAGGIO                                                                           #
      // ------------------------------------------------------------------------------------------- //
      return back()
        ->with('success_message', 'Transazione avvenuta con successo. ID transazione:'
                    . $transaction -> id
                  );

    // IN CASO DI NON SUCCESSO DELLA TRANSAZIONE
    } else {
        $errorString = "";
        foreach ($result->errors->deepAll() as $error) {
            $errorString .= 'Error: ' . $error->code . ": " . $error->message . "\n";
        }

        return back()->withErrors('An error occurred with the message: ' . $result->message);
    }

})->name('payment.send')->middleware('auth');
