<?php

namespace App\Telegram\Commands;

use Illuminate\Http\Request;

interface CommandInterface
{
    public function run(Request $request);
}
