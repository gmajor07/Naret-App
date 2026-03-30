<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Expense extends Model
{
    use HasFactory,  SoftDeletes;
    protected $fillable = ['description', 'amount', 'date'];


    public function casualLabour()
    {
        return $this->hasOne(CasualLabour::class);
       // , 'expense_id'
    }

}
