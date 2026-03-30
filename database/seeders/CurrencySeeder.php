<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            ['id' => 1, 'name' => 'TZS','description' => 'Tanzanian Shillings'],
            ['id' => 2, 'name' => 'USD','description' => 'US Dollar'],
        ];

        foreach ($items as $item) {
            Currency::create($item);
        }
    }
}
