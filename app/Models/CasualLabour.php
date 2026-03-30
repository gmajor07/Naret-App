<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CasualLabour extends Model
{
    use HasFactory, SoftDeletes;
   // protected $fillable = ['description', 'labour_charge', 'administration_fee','quantity'
     // ,'vat','payable_amount','status','expense_id'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function invoice() {
        return $this->hasOne(Invoice::class);
    }

    public function orders(){

       return $this->belongsToMany(Order::class, 'casual_labour_orders')
       ->using(CasualLabourOrder::class)
       ->as('casual_labour_order');
    }

    public function expense()
    {
        return $this->belongsTo(Expense::class);
        //, 'expense_id'
    }



}

