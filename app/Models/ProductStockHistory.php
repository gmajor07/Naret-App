<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductStockHistory extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'product_stock_history';

    public function product(){
        return $this->belongsTo(Product::class);
      }
}
