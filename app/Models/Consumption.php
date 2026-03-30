<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Prompts\Prompt;

class Consumption extends Model
{
    use HasFactory, SoftDeletes;

    public function user (){
        return $this->belongsToMany(User::class, 'fumigation_consumptions','consumption_id','fumigator_id')
        ->withPivot('fumigation_id','container_quantity')->withTimestamps();
    }

    public function fumigations (){
        return $this->belongsToMany(Fumigation::class, 'fumigation_consumptions','consumption_id','fumigation_id')
        ->withPivot('fumigation_id','fumigator_id','container_quantity')->withTimestamps();
    }

    public function product (){

        return $this->belongsTo(Product::class);
    }
}
