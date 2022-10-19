<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

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
    public function definition()
    {
        return [
            //
            "sku" => $this->faker->regexify('[A-Za-z0-9-]{20}'),
            "name" => $this->faker->word(),
            "price" => $this->faker->randomFloat(2,10, 3000),
            "stock" => 1000
        ];
    }
}
