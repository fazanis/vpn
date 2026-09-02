<?php

namespace App\Console\Commands;

use App\Jobs\DeviseSincJob;
use App\Models\Server;
use App\Services\Xui\XuiFactory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class UpdateCommand extends Command
{

    protected $signature = 'update';


    protected $description = 'Update';


    public function handle()
    {
        $artisan = Artisan::call('migrate');
        $this->info($artisan);
        $servers = Server::query()->get();
        foreach ($servers as $server) {
            $xui = XuiFactory::make($server);
            $imbounds=$xui->getInbounds($server);

            if ($imbounds==null){
                $server->update(['status'=>'inactive']);
                continue;
            }
            if($imbounds->json('obj')==[]){
                $server->update(['status'=>'inactive']);
                continue;
            }
            $server->update(['status'=>'active']);
            $server->inbounds()->delete();
            foreach($xui->subTemplate($server) as $item)
            {
                $server->inbounds()->create(['sub_template'=>$item]);
            }
            DeviseSincJob::dispatch($server)->onQueue('low');
        }
    }
}
