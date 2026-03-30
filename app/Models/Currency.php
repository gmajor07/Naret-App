<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Currency extends Model
{
    use HasFactory, SoftDeletes;

    public function account()
      {
          return $this->hasMany(Account::class);
      }

      public function invoice()
      {
          return $this->hasMany(Invoice::class);
      }

}
