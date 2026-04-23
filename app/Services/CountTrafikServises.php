<?php

namespace App\Services;

use App\Models\Devise;
use App\Models\Server;

class CountTrafikServises
{
    public function __construct(
        private XuiServices $xui
    ) {}
    public function count(Devise $devise,Server $server)
    {
        $result = $this->xui->clientTraffikById($server,$devise)['obj'];
        $total = array_sum(array_column($result, 'allTime'));
        $devise->update(['trafik'=>$total]);
    }
}
