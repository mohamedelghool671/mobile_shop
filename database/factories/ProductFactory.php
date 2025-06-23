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
        $name = $this->faker->name();
        return [
            "name" => $name,
            "description" => $this->faker->sentence(6,true),
            "image" =>'products/uSW4FcDibTHsen2yV44MhtRsCnJomRBk1GlJ3whT.jpg',
            "price" => $this->faker->randomDigit() * 10 ,
            "quantity" => $this->faker->randomDigit(),
            "category_id" =>22

        ];
    }
}
