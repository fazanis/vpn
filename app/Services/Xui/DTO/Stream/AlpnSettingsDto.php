<?php

namespace App\Services\Xui\DTO\Stream;

class AlpnSettingsDto
{
    public function __construct(
        public int $inbound,
        public int $port,
        public string $protocol,
        public string $alpn,
        public string $encryption,
        public string $security,
    ){}
    public static function toArray($data)
    {
        $streamSettings=self::normalize($data['streamSettings'] ?? []);
        $settings=self::normalize($data['settings'] ?? []);
        return new self(
            inbound: $data['id'],
            port: 443,
            protocol: $data['protocol'],
            alpn: 'alpn',
            encryption: $settings['encryption'],
            security: $streamSettings['security'],
        );
    }

    private static function normalize(array|string|null $value): array
    {
        if (is_array($value)) {
            return $value;
        }

        return json_decode($value ?? '[]', true) ?: [];
    }
}
