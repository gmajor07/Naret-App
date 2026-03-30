<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['customer_id','order_date','order_number','po_number','description'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            $year = now()->format('Y');
            $lastOrder = self::latest('id')->first();

            if ($lastOrder) {
                $lastId = $lastOrder->id;
                $incrementNumber = str_pad($lastId % 10000 + 1, 4, '0', STR_PAD_LEFT);
                $order->order_number = $year .'-ORD'. '-' . $incrementNumber;
            } else {
                $order->order_number = $year .'-ORD'. '-0001';
            }
        });
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function products() {
        return $this->belongsToMany(Product::class,'order_products')
        ->withPivot('quantity')->using(OrderProducts::class)->as('order_products');
    }

    public function fumigations() {
        return $this->belongsToMany(Fumigation::class,'fumigation_orders');
       /*  ->using(FumigationOrder::class)->as('fumigation_orders'); */
    }

    public function invoice() {
        return $this->hasOne(Invoice::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function type (){
        return $this->belongsTo(Type::class);
    }
    public function casual_labour() {
        return $this->belongsToMany(CasualLabour::class,'casual_labour_orders')
       ->using(CasualLabourOrder::class)->as('casual_labour_orders');
    }

}
