<?php

namespace RayzenAI\GoldSilverPrice\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use RayzenAI\GoldSilverPrice\Models\GoldPrice;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\RayzenAI\GoldSilverPrice\Models\GoldPrice>
 */
class GoldPriceFactory extends Factory
{
    protected $model = GoldPrice::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'date' => fake()->dateTimeBetween('-30 days', 'now'),
            'gold_per_tola' => fake()->numberBetween(230000, 250000),
            'gold_per_10g' => fake()->numberBetween(197000, 214000),
            'silver_per_tola' => fake()->numberBetween(2800, 3200),
            'silver_per_10g' => fake()->numberBetween(2400, 2750),
        ];
    }
}
