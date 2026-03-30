<?php

namespace Database\Seeders;

use App\Models\Type;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            ['id' => 1, 'name' => 'product'],
            ['id' => 2, 'name' => 'fumigation'],
            ['id' => 3, 'name' => 'casual'],
        ];

        foreach ($items as $item) {
            Type::create($item);
        }
    }
}
