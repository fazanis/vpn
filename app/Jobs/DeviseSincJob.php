<?php

namespace App\Jobs;

use App\Models\Devise;
use App\Models\ServerInbound;
use App\Models\User;
use App\Services\DeviseUpdateServises;
use App\Services\XuiServices;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class DeviseSincJob implements ShouldQueue
{
    use Queueable;
    
   
    public function handle(DeviseUpdateServises $devise): void
    {
        $devise->handle();
    }
}
