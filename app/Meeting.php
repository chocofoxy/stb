<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
  protected $fillable = [
    'date',
    'user'
  ];

  public function user()
    {
        return $this->belongsTo('App\User');
    }
}
