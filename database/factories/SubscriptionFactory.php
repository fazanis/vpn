<?php

namespace Database\Factories;

use App\Models\Tariff;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class SubscriptionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'tariff_id' =>Tariff::factory(),
            'type' => $this->faker->randomElement(['pay','trial']),
            'status' => $this->faker->randomElement([1,2,3]),
            'days' => 30,
            'key'=>'',
            'max_devices'=>$this->faker->randomElement([5,30]),
            'expires_at' => now()->subDay(),
        ];
    }


}
