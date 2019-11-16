<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Feature;

class Apartment extends Model
{
  protected $fillable = [
    'description',
    'rooms',
    'beds',
    'bathrooms',
    'mq',
    'address',
    'visualizations',
    'active',
    'user_id',
    'tier_id',
    'img_path',
    'lat',
    'lon'
  ];

  public function features(){

    return $this -> belongsToMany(Feature::class);
  }

  public function user(){

    return $this -> belongsTo(User::class);
  }

  public function tier(){

    return $this -> belongsTo(Tier::class);
  }

  public function messages(){

    return $this -> hasMany(Message::class);
  }

  public function payments(){

    return $this -> hasMany(Payment::class);
  }
}
