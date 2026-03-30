<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items=[
            ['id' => 1, 'first_name' => 'Rose',  'last_name' => 'Ngitoria', 'email' => 'rngitoria@gmail.com', 'password' => bcrypt('12345678'), 'role_id' => '1', 'status' => '1'],
        ];

        foreach ($items as $item) {
            User::create($item);
        }
    }
}
