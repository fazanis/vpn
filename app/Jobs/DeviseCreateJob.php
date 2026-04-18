<?php

namespace App\Jobs;


use App\Models\Devise;
use App\Services\DevisesCreateServises;
use App\Services\DevisesSincServises;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class DeviseCreateJob implements ShouldQueue
{
    use Queueable;

    public $devise;
    public function __construct(Devise $devise)
    {
        $this->devise = $devise;
    }

    public function handle(DevisesCreateServises $devisesCreate): void
    {
        $devisesCreate->handle($this->devise);
    }
}
