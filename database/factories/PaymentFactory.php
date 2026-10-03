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
class PaymentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id'=>User::factory(),
            'tariff_id'=>Tariff::factory(),
            'fk_order_id'=>rand(1,10),
            'amount'=>$this->faker->numberBetween(1,100),
            'status'=>$this->faker->randomElement([0,1]),
            'intid'=>$this->faker->randomElement([1,2,3,4,5,6,7,8,9]),
            'paid_at'=>$this->faker->dateTime(),
        ];
    }


}
