<?php

namespace App\Http\Controllers;
use App\Apartment;
use App\Message;
use App\User;

use Illuminate\Http\Request;

class MessageController extends Controller
{
  public function storeMessage(Request $request, $id){

    $validatedData = $request->validate([

        'sender_email'=> 'required',
        'body' => 'required'
    ]);

    $validatedData['apartment_id'] = $id;

    $apt = Apartment::findOrFail($id);

    $newMsg = Message::create($validatedData);

    $newMsg -> apartment() -> associate($apt) ;
    $newMsg -> save(); 

    return redirect()->back();
  }
}
