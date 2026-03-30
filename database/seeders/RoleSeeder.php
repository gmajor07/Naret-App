<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            ['id' => 1, 'name' => 'Administrator', 'description' => 'Admin'],
            ['id' => 2, 'name' => 'Seller', 'description' => 'Seller'],
            ['id' => 3, 'name' => 'Fumigator', 'description' => 'Fumigator'],
        ];

        foreach ($items as $item) {
            Role::create($item);
        }
    }
}
