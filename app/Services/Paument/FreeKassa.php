<?php

namespace App\Services\Paument;

class FreeKassa
{
    public function paumentLink($summa,$orderId,$currency='RUB')
    {
        $shopId=env('FREEKASSA_SHOP_ID');
        $api_key = env('FREEKASSA_API_KEY');
        $secret_key = env('FREEKASSA_SECRET_KEY');

        $link = "https://pay.fk.money/?m=$shopId&oa=$summa&currency=$currency&o=$orderId&em=fazanis@mail.ru&us_user=1&us_tarif=6&s=".md5("$shopId:$summa:$secret_key:$currency:$orderId");
       return $link;
    }
}
