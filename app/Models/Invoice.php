<?php

namespace App\Models;


use App\Models\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;
    public const TAX_TYPE_VAT = 'vat';
    public const TAX_TYPE_WITHOUT_VAT = 'without_vat';
    public const TAX_TYPE_EXEMPT = 'exempt';

    protected $fillable = ['customer_id','order_id','invoice_number','due_date',
     'payment_status','total_vat_inclusive','vat','is_non_vat','tax_type','discount','total_vat_exclusive','amount_paid'
    ,'amount_due','invoice_satus','due_date'];

    protected $casts = [
        'is_non_vat' => 'boolean',
    ];

    public function getTaxTypeAttribute($value): string
    {
        if ($value) {
            return $value;
        }

        return $this->is_non_vat
            ? self::TAX_TYPE_EXEMPT
            : ((float) $this->vat > 0 ? self::TAX_TYPE_VAT : self::TAX_TYPE_WITHOUT_VAT);
    }

    public function taxLabel(): string
    {
        return match ($this->tax_type) {
            self::TAX_TYPE_VAT => 'VAT (18%)',
            self::TAX_TYPE_EXEMPT => 'VAT EXEMPT',
            default => 'WITHOUT VAT',
        };
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($invoice) {
            if (! empty($invoice->invoice_number)) {
                return;
            }

            $expectedNumber = $invoice->expectedInvoiceNumberFromOrder();

            if ($expectedNumber) {
                $invoice->invoice_number = $expectedNumber;
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

        static::saving(function ($invoice) {
            $expectedNumber = $invoice->expectedInvoiceNumberFromOrder();

            if ($expectedNumber) {
                $invoice->invoice_number = $expectedNumber;
            }
        });
    }

    public function expectedInvoiceNumberFromOrder(): ?string
    {
        $order = $this->relationLoaded('order')
            ? $this->order
            : ($this->order_id ? Order::find($this->order_id) : null);

        if ($order && preg_match('/^(\d{4})-ORD-(\d{4})$/', $order->order_number, $matches)) {
            return $matches[1] . '-' . $matches[2];
        }

        return null;
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
