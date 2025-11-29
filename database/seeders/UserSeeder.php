<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $admin = User::create([
            'name' => 'Baldon',
            'email' => 'freyja.feb13@gmail.com',
            'password' => bcrypt('admin123'), // change if you want
            'status' => 'active',
        ]);

        $admin->assignRole('Admin');
    }
}
