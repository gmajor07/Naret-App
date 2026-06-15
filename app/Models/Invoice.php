<?php

namespace App\Models;


use App\Models\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['customer_id','order_id','invoice_number','due_date',
     'payment_status','total_vat_inclusive','vat','is_non_vat','discount','total_vat_exclusive','amount_paid'
    ,'amount_due','invoice_satus','due_date'];

    protected $casts = [
        'is_non_vat' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($invoice) {
            if (! empty($invoice->invoice_number)) {
                return;
            }

            $order = $invoice->relationLoaded('order')
                ? $invoice->order
                : ($invoice->order_id ? Order::find($invoice->order_id) : null);

            if ($order && preg_match('/^(\d{4})-ORD-(\d{4})$/', $order->order_number, $matches)) {
                $invoice->invoice_number = $matches[1] . '-' . $matches[2];
                return;
            }

            $year = now()->format('Y');
            $lastInvoice = self::latest('id')->first();

            if ($lastInvoice) {
                $lastId = $lastInvoice->id;
                $incrementNumber = str_pad($lastId % 10000 + 1, 4, '0', STR_PAD_LEFT);
                $invoice->invoice_number = $year . '-' . $incrementNumber;
            } else {
                $invoice->invoice_number = $year . '-0001';
            }
        });
    }


    public function order(){
        return $this->belongsTo(Order::class);
      }

      public function customer()
      {
          return $this->belongsTo(Customer::class);
      }

      public function sales()
      {
          return $this->hasMany(Sale::class);
      }

      public function currency(){
        return $this->belongsTo(Currency::class);
      }
      public function type(){
        return $this->belongsTo(Type::class);
      }
}
