<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tier extends Model
{
  protected $fillable = [
    'price',
    'level',
    'duration',
  ];

  public function apartaments(){

    return $this -> hasMany(Apartment::class);
  }

  // public function payments(){
  //
  //   return $this -> hasMany(Payment::class);
  // }
}
