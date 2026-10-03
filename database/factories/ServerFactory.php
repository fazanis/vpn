<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Testing\Fakes\Fake;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class ServerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'=>fake()->name(),
            'type'=>fake()->randomElement(['https','http']),
            'ip'=>fake()->ipv4(),
            'port'=>\fake()->randomElement(['2929','3939']),
            'folder'=>Str::random(20),
            'flag'=>fake()->randomElement(['🇳🇱','🇩🇪']),
            'status'=>fake()->randomElement(['inactive','active']),
            'imbound'=>1,
            'login'=>fake()->word(),
            'password'=>fake()->word(),
            'token'=>fake()->word(),
            'priority'=>$this->faker->randomElement(['1','2','3','4','5','6','7','8','9']),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
