<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Supplier;
use Faker\Generator as Faker;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'brand_id' => Brand::pluck('id')->random(),
            'supplier_id' => Supplier::pluck('id')->random(),
            'description' => $this->faker->sentence,
            'cost' => $this->faker->numberBetween(10, 100),
            'img_path' => $this->faker->imageUrl(),
        ];
    }
}
