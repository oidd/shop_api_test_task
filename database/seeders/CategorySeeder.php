<?php

namespace Database\Seeders;

use App\Models\Category as Model;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $name = [
            'First',
            'Second',
            'Third',
            'Fourth'
        ];

        foreach ($name as $i)
            Model::create([
                'name' => $i,
            ]);
    }
}
