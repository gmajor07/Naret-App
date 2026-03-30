<?php
namespace App\Rules;
use Illuminate\Contracts\Validation\Rule;

class DiscountAmountLessThanTotal implements Rule
{
    public function passes($attribute, $value)
    {
        $unitPrices = request()->input('unit_price');
        $quantities = request()->input('item_quantity');

        $total = 0;

        // Calculate the total based on unit_price * item_quantity
        foreach ($unitPrices as $key => $unitPrice) {
            $total += $unitPrice * $quantities[$key];
        }

        // Check if discount_amount is less than the total
        return $value < $total;
    }

    public function message()
    {
        return 'The discount amount must be less than the total of (unit_price * item_quantity).';
    }
}
