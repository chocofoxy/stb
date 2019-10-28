<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
  protected $fillable = [
    'type',
    'file' ,
    'owner' ,
    'accepted' ,
    'confirmed' ,
    'montant' ,
    'periode'
  ];
}
