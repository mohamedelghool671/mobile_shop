<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
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
        $name = $this->faker->words(2,true);
        return [
            "name" => $name,
            "description" => $this->faker->sentence(6,true),
            "image" => $this->faker->imageUrl(300,300),
            "image1" => $this->faker->imageUrl(300,300),
            "image2" => $this->faker->imageUrl(300,300),
            "image3" => $this->faker->imageUrl(300,300),
            "price" => $this->faker->randomDigit() * 10 ,
            "quantity" => $this->faker->randomDigit(),
            "category_id" =>22

        ];
    }
}
