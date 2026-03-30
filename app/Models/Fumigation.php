<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Fumigation extends Model
{
    use HasFactory, SoftDeletes;

   /*  protected $fillable = [
        'description', // Add 'description' here
        // Add other fillable attributes if any
    ];
    protected $attributes = [
        'item_quantity' => 0,
        'unit_price' => 0,
        'fumigation_date' => Null,
        'status' => 0,

    ]; */

  /*   public function __construct(array $attributes = [])
    {
        $this->attributes['fumigation_date'] = now();
        parent::__construct($attributes);
    } */

        protected static function boot()
        {
            parent::boot();

            static::creating(function ($fumigation) {
                $year = now()->format('Y');
                $lastFumigation = self::latest('id')->first();

                if ($lastFumigation) {
                    $lastId = $lastFumigation->id;
                    $incrementNumber = str_pad($lastId % 10000 + 1, 4, '0', STR_PAD_LEFT);
                    $fumigation->fumigation_number = $year .'-FMGN'. '-' . $incrementNumber;
                } else {
                    $fumigation->fumigation_number = $year .'-FMGN'. '-0001';
                }
            });
        }

        public function products() {
            return $this->belongsToMany(Product::class,'fumigation_products')
            ->withPivot('quantity')->using(Fumigation_Product::class)->as('fumigation_products');
        }

        public function customer()
        {
            return $this->belongsTo(Customer::class);
        }

        public function invoice() {
            return $this->hasOne(Invoice::class);
        }

        public function orders(){
            return $this->belongsToMany(Order::class,'fumigation_orders');
         /*    ->using(FumigationOrder::class)->as('fumigation_orders'); */
        }


        public function user ()  {
            return $this->belongsToMany(User::class, 'fumigation_consumptions', 'fumigation_id', 'fumigator_id')
                ->withPivot('container_quantity');
        }


        /* {
            return $this->belongsToMany(User::class, 'fumigation_consumptions','fumigation_id','fumigator_id','consumption_id')
            ->withPivot('fumigation_id','fumigator_id','consumption_id','container_quantity')->withTimestamps();
        } */


}


