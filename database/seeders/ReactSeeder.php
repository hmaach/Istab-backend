<?php

namespace Database\Seeders;

use App\Models\React;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        React::factory()
            ->count(60)
            ->create();
    }
}
