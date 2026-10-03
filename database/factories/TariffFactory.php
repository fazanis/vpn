<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class TariffFactory extends Factory
{

    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name'=>$this->faker->name(),
            'price'=>$this->faker->numberBetween(0,100),
            'is_trial'=>$this->faker->boolean(),
            'duration_days'=>$this->faker->numberBetween(1,30),
            'max_devices'=>$this->faker->numberBetween(0,5),
        ];
    }


}
