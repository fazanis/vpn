<?php

namespace App\Console\Commands;

use App\Models\Server;
use App\Models\User;
use App\Services\XuiServices;
use Illuminate\Console\Command;

class BalansesServersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'balanses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $servers = Server::where('status','active')->get();
        $users = User::get();
        $xuiServices = new XuiServices();
        $result='';
        $traff=0;
        foreach($servers as $server){
            foreach($users as $user){
            if(!$xuiServices->clientExists($server,$user)){
                $xuiServices->addClient($server,$user);
            }
            $result.= $xuiServices->getConnect($server,$user).'#'.$server->name.''.$server->flag;
            // dd($xuiServices->clientTraffikById($server,$user));
            $traff+= data_get(
                $xuiServices->clientTraffikById($server, $user),
                'obj.0.allTime',
                0
            );
            }
            
        }
        $this->info('balanses2');
    }
}
