<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\StatusType;

use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Brand::create([
            'name' => 'genérico',
            'status_id' => StatusType::where('name', 'activo')->first()->id,
            'description' => 'Marca genérica',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
