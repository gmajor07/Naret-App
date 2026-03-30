<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Product_stock_history;

class Product extends Model
{
    use HasFactory,SoftDeletes;

    /* public function product_histories(){
        return $this->hasMany(Product_stock_history::class);
      } */

    protected $fillable = ['name', 'description','stock_quantity',
                             'unit_measure_id','unity_price'];

    public function orders()
    {
        return $this->belongsToMany(Order::class,'order_products')->withPivot('quantity')
        ->using(OrderProducts::class)->as('order_products');
    }
//(,'order_products')->using(OrderProducts::class)->as('order_products');

    public function type (){
        return $this->belongsTo(Type::class);
    }

    public function unit_measure (){
        return $this->belongsTo(Unit_measure::class);
    }

    public function consumption()
    {
        return $this->hasOne(Consumption::class);
    }
}
