<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin'),
            'phone_number' => '967777777777',
        ]);

        DB::table('users')->insert([
            'name' => 'hadi',
            'email' => 'hadi@gmail.com',
            'password' => Hash::make('hadi'),
            'phone_number' => '967771445349',
        ]);
    }
}
