<?php

namespace App\Models;

use App\Models\Invoice;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sale extends Model
{
    use HasFactory, SoftDeletes;

   /*  public function product(){
      return $this->belongsTo(Product::class);
    } */

    public function invoice(){
        return $this->belongsTo(Invoice::class);
    }

    public function customer(){
        return $this->belongsTo(Customer::class);
    }
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

}
