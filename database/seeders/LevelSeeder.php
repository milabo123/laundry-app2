<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Level;

class LevelSeeder extends Seeder
{
    public function run(): void
    {
        $levels = [
            ['id' => 1, 'level_name' => 'Administrator'],
            ['id' => 2, 'level_name' => 'Operator'],
            ['id' => 3, 'level_name' => 'Pimpinan'],
        ];

        foreach ($levels as $level) {
            Level::updateOrCreate(['id' => $level['id']], $level);
        }
    }
}
