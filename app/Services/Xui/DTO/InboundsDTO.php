<?php

namespace App\Services\Xui\DTO;

use App\Services\Xui\DTO\Stream\AlpnSettingsDto;
use App\Services\Xui\DTO\Stream\TcpSettingsDto;
use App\Services\Xui\DTO\Stream\XhttpSettingsDto;

final class InboundsDTO
{


    public static function make($data)
    {
//        dd($data);
        $result = self::normalize($data['streamSettings']);
        return match ($result['network']) {
            'xhttp' => XhttpSettingsDto::toArray($data),
            'tcp' => TcpSettingsDto::toArray($data),
        };
    }


    private static function normalize(array|string|null $value): array
    {
        if (is_array($value)) {
            return $value;
        }

        return json_decode($value ?? '[]', true) ?: [];
    }
}
