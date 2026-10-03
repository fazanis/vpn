<?php

namespace Database\Factories;

use App\Models\Server;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Testing\Fakes\Fake;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class ServerInboundFactory extends Factory
{
    public function definition(): array
    {
        return [
            'server_id'=>Server::query()->inRandomOrder()->first(),
            'sub_template'=>'vless://00000000-0000-0000-0000-000000000000@127.0.0.1:1?type=tcp&encryption=none',
        ];
    }

}
