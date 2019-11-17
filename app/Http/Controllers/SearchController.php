<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Apartment;
use App\Feature;
use Illuminate\Database\Eloquent\Builder;
use GuzzleHttp\Client;
class SearchController extends Controller
{
    public function search(Request $request){

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
            $query->where('features.id', $featID);
          });
        }
      }


      $foundApts = $foundApts -> get();

      //trovo la distanza tra appartamenti
      if($request->lat && $request->lon){
        $lat = $request->lat;
        $lon = $request->lon;
        $list=[];
        foreach ($foundApts as $index=>$apt) {

          if ($this->getDistance($lat, $lon, $apt->toArray()) <= 20 ){
             $list[] = $apt;

           }
        }

        return response()->json($list, 200);

      }

      return response()->json($foundApts, 200);




    }




    //funzione per calcolare la distanza tra due punti
    public function getDistance($lat1, $lon1, $apartment){
      $lat2 = $apartment['lat'];
      $lon2 = $apartment['lon'];

      if($lat1 == $lat2 && $lon1 == $lon2){return 0;}

      $p1 = deg2rad($lat1);
      $p2 = deg2rad($lat2);
      $dp = deg2rad($lat2 - $lat1);
      $dl = deg2rad($lon2 - $lon1);
      $a = (sin($dp/2) * sin($dp/2)) + (cos($p1) * cos($p2) * sin($dl/2) * sin($dl/2));
      $c = 2 * atan2(sqrt($a),sqrt(1-$a));
      $r = 6371008;
      $d = $r * $c;
      return $d/1000;
    }



    /**
    * Chiede a TomTom latitudine, longitudine e mappa
    * richiede array
    * restituisce array
    **/
    public function getMapData($validatedApt){

    // Recupera coordinate e mappa
    $apiKey = env('TOMTOM_APIKEY');
    $tomtom = new Client(['base_uri' => 'https://api.tomtom.com']);

    $response = $tomtom->request('GET',
                                '/search/2/geocode/'. $validatedApt['address'] . '.json',
                                [
                                  'query'=> [
                                    'key'=>$apiKey,
                                    'extendedPostalCodesFor'=>'PAD',
                                    'limit'=>'1'
                                    ]
                                  ]);
    $body = json_decode($response->getBody(), true);

    if ( $body['results']){
          //recupero lat e lon
          $positions = $body['results'][0]['position'];
          $lat = $positions['lat'];
          $lon = $positions['lon'];
          $validatedApt['lat'] = $lat;
          $validatedApt['lon'] = $lon;


          //recupero la mappa
          $response = $tomtom->request('GET',
                                        '/map/1/staticimage',
                                        [
                                          'query' => [
                                            'key'=>$apiKey,
                                            'layer' => 'hybrid',
                                            'style' => 'main',
                                            'format' => 'png',
                                            'zoom' => '17',
                                            'center' => $lon.', '.$lat,
                                            'width' => '512',
                                            'height' => '512',
                                            'view' => 'Unified',
                                          ]
                                        ]);

        $fileName =  "map-" . uniqid() .".png";

        file_put_contents('img/'. $fileName, $response->getBody()->getContents());

        $validatedApt['map_img_path']=$fileName;

    }

      return ($validatedApt);

  }
  //


  // /**
  // * Chiede a TomTom mappa per front
  // **/
  public function getMapFront(Request $request, $id){
  //
  // //Recupera coordinate e mappa
  $apiKey = env('TOMTOM_APIKEY');
  //
  $tomtom = new Client(['base_uri' => 'https://api.tomtom.com']);
  //
  $response = $tomtom->request('GET',
                              '/search/2/geocode/'. $request -> address . '.json',
                              [
                                'query'=> [
                                  'key'=>$apiKey,
                                  'extendedPostalCodesFor'=>'PAD',
                                  'limit'=>'1'
                                  ]
                                ]);
  $body = json_decode($response->getBody(), true);
  //
  if ( $body['results']){
    //recupero lat e lon
    $positions = $body['results'][0]['position'];
    $lat = $positions['lat'];
    $lon = $positions['lon'];

      //recupero la mappa
      $responseMap = $tomtom->request('GET',
                                    '/map/1/staticimage',
                                    [
                                      'query' => [
                                        'key'=>$apiKey,
                                        'layer' => 'hybrid',
                                        'style' => 'main',
                                        'format' => 'png',
                                        'zoom' => '7',
                                        'center' => $lon.', '.$lat,
                                        'width' => '512',
                                        'height' => '512',
                                        'view' => 'Unified',
                                      ]
                                    ]);
     // Cerco img_map_path nel database
     $pat_img = Apartment::findOrFail($id) -> map_img_path;
     if($pat_img){
         return response()->json([
           $request -> address,
           "body" => $body,
           // "mappa" => $jsonMap,
           "filename" => $pat_img,
           $lat,
           $lon
         ]);
   }
  }
    return response()->json([
      "Couldn't find map data"
    ],404);

  }




}
