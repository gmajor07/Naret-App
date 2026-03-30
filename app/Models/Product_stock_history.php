<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product_stock_history extends Model
{
    use HasFactory, SoftDeletes;

    public function product(){
        return $this->belongsTo(Product::class);
    }

}
