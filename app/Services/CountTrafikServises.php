<?php

namespace App\Services;

use App\Models\Devise;
use App\Models\Server;
use Carbon\Carbon;

class CountTrafikServises
{
    public function __construct(
        private XuiServices $xui
    ) {}
    public function count(Devise $devise,Server $server)
    {
        $result = $this->xui->clientTraffikById($server,$devise)['obj'];

//        dd(Carbon::createFromTimestamp(array_column($result, 'lastOnline')[0]/ 1000)->format('Y-m-d H:i:s'));
        $total = array_sum(array_column($result, 'allTime'));
        return $total;
    }
}
