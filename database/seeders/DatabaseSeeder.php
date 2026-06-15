<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Merchant;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Merchant::create([
            'name' => 'Demo Merchant'
        ]);
    }
}