<?php

namespace App\Models;

use App\Models\Sale;
use App\Models\Order;
use App\Models\Invoice;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

/*     public function invoices(){
        return $this->hasMany(Invoice::class);
      }

      public function sales(){
        return $this->hasMany(Sale::class);
      } */

      protected $fillable = ['name','tin_number','vrn','email','phone','location'];

      public function orders()
      {
            return $this->hasMany(Order::class);

      }

      public function invoice()
      {
          return $this->hasMany(Invoice::class);
      }

      public function sales()
      {
            return $this->hasMany(Sale::class);

      }

}
