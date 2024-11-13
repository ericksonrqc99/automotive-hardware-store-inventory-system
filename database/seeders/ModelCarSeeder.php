<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\ModelCar;
use App\Models\StatusType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ModelCarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ModelCar::create([
            'name' => 'genérico',
            'year' => now()->year,
            'status_id' => StatusType::where('name', 'activo')->first()->id,
            'brand_id' => Brand::where('name', 'genérico')->first()->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
