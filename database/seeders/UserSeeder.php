<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(['email' => 'admin@laundry.com'], [
            'id_level' => 1,
            'name'     => 'Administrator',
            'password' => Hash::make('admin123'),
        ]);

        User::updateOrCreate(['email' => 'operator@laundry.com'], [
            'id_level' => 2,
            'name'     => 'Operator Laundry',
            'password' => Hash::make('operator123'),
        ]);

        User::updateOrCreate(['email' => 'pimpinan@laundry.com'], [
            'id_level' => 3,
            'name'     => 'Pimpinan Laundry',
            'password' => Hash::make('pimpinan123'),
        ]);
    }
}
