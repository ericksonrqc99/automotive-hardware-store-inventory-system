<?php

namespace Database\Seeders;

use App\Models\StatusType;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StatusType::create([
            'name' => 'activo',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        StatusType::create([
            'name' => 'inactivo',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
