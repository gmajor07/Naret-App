<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Type extends Model
{
    use HasFactory,  SoftDeletes;
    protected $fillable = ['name'];

    public function product (){
        return $this->hasOne(Product::class);
    }

    public function order (){
        return $this->hasOne(Order::class);
    }
    public function invoice (){
        return $this->hasOne(Invoice::class);
    }
}
