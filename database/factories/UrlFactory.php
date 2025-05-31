<?php

namespace Database\Factories;

use App\Models\Url;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Url>
 */
class UrlFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Url::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'original_url' => $this->faker->url,
            'short_code' => Str::random(6),
            'expires_at' => Carbon::now()->addDays(30),
            'is_active' => true,
            'click_count' => $this->faker->numberBetween(0, 1000),
        ];
    }

    /**
     * Indicate that the URL is expired.
     *
     * @return Factory
     */
    public function expired(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'expires_at' => Carbon::now()->subDays(rand(1, 30)),
            ];
        });
    }

    /**
     * Indicate that the URL is inactive.
     *
     * @return Factory
     */
    public function inactive(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'is_active' => false,
            ];
        });
    }

    /**
     * Indicate that the URL is created by a guest.
     *
     * @return Factory
     */
    public function guest(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'user_id' => null,
            ];
        });
    }
}
