<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Support\Facades\Storage;
use Faker\Factory as Faker;
use DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    // public function run()
    // {
    //     Product::factory(200)->create();

    //     for ($i = 1; $i <= 100; $i++) {
    //         Stock::factory()->create([
    //             'product_id' => $i
    //         ]);
    //     }
    // }
}
