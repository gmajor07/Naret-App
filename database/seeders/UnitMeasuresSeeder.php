<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Unit_measure;

class UnitMeasuresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            ['id' => 1, 'name' => 'Boxes', 'description' => 'Boxes'],
            ['id' => 2, 'name' => 'Bottle', 'description' => 'Bottle'],
        ];

        foreach ($items as $item) {
            Unit_measure::create($item);
        }
    }

}
