<?php

namespace App\Jobs;

use App\Models\Devise;
use App\Services\DeviseDeleteServises;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class DeviseDeleteJob implements ShouldQueue
{
    use Queueable;
    public $devise;
    public function __construct(Devise $devise)
    {
        $this->devise = $devise;
    }

    public function handle(DeviseDeleteServises $deviseDelete): void
    {
        $deviseDelete->handle($this->devise);
    }
}
