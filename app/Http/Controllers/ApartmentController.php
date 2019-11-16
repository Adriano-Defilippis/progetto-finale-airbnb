<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Apartment;
use App\Feature;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Requests\ApartmentRequest;

use Braintree_Transaction;

class ApartmentController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $validated = $request->validate([
          'beds'=> 'required|numeric|min:1',
          'bathrooms'=>'required|numeric|min:1',
          'rooms'=> 'required|numeric|min:1'
        ]);

        $foundApts = new Apartment;

        //cerco solo appartamenti attivi
        $foundApts = $foundApts->where('active','>','0');

        //ciclo i valori della request
        foreach ($validated as $key => $value) {
            $foundApts = $foundApts->where($key, '>=', $value);
        }

        //se sono state selezionate features...
        if($request->features){
          //...cicla e filtra gli appartamenti
          foreach ($request->features as $featID){
            $foundApts = $foundApts->whereHas('features', function(Builder $query) use($featID){
              $query->where('features.id', '=', $featID);
            });
          }
        }

        $foundApts = $foundApts -> get();


        dd($foundApts);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $file= $request->file('file');

        $features= Feature::all();

        return view('aptcreate_address', compact('features'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ApartmentRequest $request)
    {


        $validatedApt = $request->validated();


        $validatedApt['user_id'] = $request -> user() -> id;
        $validatedApt['visualizations'] = 0;
        $validatedApt['tier_id'] = 1;

        //aggiungo la path per l'immagine
        $file = $request -> file('img');

        if ($file) {

          $targetPath = 'img/uploads';
          $targetFile = rand(0,1000) . "apt." . $file->getClientOriginalExtension();

          $file->move($targetPath, $targetFile);
          // $file->fails();

          // aggungo path_img nei dati validati
          $validatedApt['img_path'] = $targetFile;
        }

        //creo la nuova entità sul db
        $newApt = Apartment::create($validatedApt);

        if($request->feature){
          //associo le features all'appartamento
          foreach ($request->feature as $feature) {


            $item = Feature::findOrFail($feature);


            $item -> apartments() -> attach($newApt);

            // dd($item);
          }

        }

        return redirect('/home');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

      // // Crea nuvo oggetto classe Braintree/gateway
      // $gateway = new Braintree\Gateway([
      //       'environment' => config('services.braintree.environment'),
      //       'merchantId' => config('services.braintree.merchantId'),
      //       'publicKey' => config('services.braintree.publicKey'),
      //       'privateKey' => config('services.braintree.privateKey')
      //   ]);

        // PER TEST, TOKEN STATICO ACCOUNT BRAINTREE
        // $token = "sandbox_7bgcfdq8_hstckbs9tty2wg8q";

        $apt = Apartment::findOrFail($id);

        return view('aptshow', compact('apt'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $apt = Apartment::findOrFail($id);

        if ($apt->user_id == Auth::id()) {
          return view('aptedit', compact('apt'));

        } else {
          return redirect('/');
        }


    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ApartmentRequest $request, $id)
    {
      $validatedApt = $request->validated();

      $apt = Apartment::findOrFail($id);

      if ($apt->user_id == Auth::id()) {

        //aggiungo la path per l'immagine
        $file = $request -> file('img');

        if ($file) {
          $targetPath = 'img/uploads';
          $targetFile = $apt->id . "apt." . $file->getClientOriginalExtension();

          $file->move($targetPath, $targetFile);

          $apt -> update([
            'img_path'=>$targetFile
          ]);
        }
        //controllo se esistono feature nella request
        if($request->feature){
          //associo le features all'appartamento
          foreach ($request->feature as $feature) {

            $item = Feature::findOrFail($feature);

            //sync() aggiorna apartment_feature senza aggiungere duplicati
            $item -> apartments() -> sync($apt,false);

          }
        }

        $apt->update($validatedApt);

        return redirect('/home');

      } else {
        return redirect('/');
      }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $apt = Apartment::findOrFail($id);

        if ($apt->user_id == Auth::id()){
          $apt->delete();
          return redirect('/home');

        } else {

          return redirect('/');

        }

    }
}
