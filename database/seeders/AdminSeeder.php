<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Service\Auth\AuthService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        (new AuthService(new Admin()))->register([
            'name'      => 'admin',
            'email'     => 'admin@admin.com',
            'password'  => 'password',
        ]);
    }
}
