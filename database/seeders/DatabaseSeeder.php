<?php

namespace Database\Seeders;

use App\Models\Feature;
use App\Models\Package;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'A123456'
        ]);

        Feature::factory()->create([
            'route_name' => 'feature-one.index',
            'name' => 'Calculator',
            'description' => 'Calculator for simple calculations'
        ]);

        Feature::factory()->create([
            'route_name' => 'feature-two.index',
            'name' => 'Calculator sum',
            'description' => 'Calculator for simple calculations'
        ]);

        DB::table('packages')->insert(
            [
                [
                    'name' => 'Basic',
                    'price' => 5,
                    'credits' => 25
                ],
                [
                    'name' => 'Silver',
                    'price' => 20,
                    'credits' => 110
                ],
                [
                    'name' => 'Basic',
                    'price' => 50,
                    'credits' => 300
                ]
            ]
        );
    }
}
