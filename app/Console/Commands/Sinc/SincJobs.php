<?php

namespace App\Console\Commands\Sinc;

use App\Models\SincJobs as ModelsSincJobs;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class SincJobs extends Command
{
    protected $signature = 'sinc-jobs';

    protected $description = 'Command description';

    public function handle()
    {
        $jobs = ModelsSincJobs::query()->get();
        foreach($jobs as $job){
            $command = app($job->command);
            Artisan::call($command->getName());
        }
    }
}
