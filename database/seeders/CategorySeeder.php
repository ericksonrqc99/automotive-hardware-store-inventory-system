<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\StatusType;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create([
            'name' => 'genérico',
            'status_id' => StatusType::where('name', 'activo')->first()->id,
            'description' => 'Productos genéricos',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
