<?php

namespace App\Services\Xui\DTO\Stream;

class XhttpSettingsDto
{
    public function __construct(
        public int $inbound,
        public int $port,
        public string $protocol,
        public string $type,
        public string $encryption,
        public string $security,
        public string $pbk,
        public string $fp,
        public string $sni,
        public string $sid,
        public string $spx,
        public string $pqv,
        public string $path,
        public string $host,
        public string $mode,
        public string $network,
    )
    {

    }
    public static function toArray($data)
    {
        $streamSettings=self::normalize($data['streamSettings'] ?? []);
        $settings=self::normalize($data['settings'] ?? []);
        $sniffing=self::normalize($data['sniffing'] ?? []);
//        dd($streamSettings['realitySettings']['serverNames'][0]);

        return new self(
            inbound: $data['id'],
            port: $data['port'],
            protocol: $data['protocol'],
            type: $streamSettings['network'],
            encryption: $settings['encryption'] ?? '',
            security: $streamSettings['security'],
            pbk: $streamSettings['realitySettings']['settings']['publicKey'] ?? '',
            fp: $streamSettings['realitySettings']['settings']['fingerprint'] ?? '',
            sni: $streamSettings['realitySettings']['serverNames'][0] ?? '',
            sid: $streamSettings['realitySettings']['shortIds'][0] ?? '',
            spx: $streamSettings['realitySettings']['settings']['spiderX'] ?? '',
            pqv: $streamSettings['realitySettings']['settings']['mldsa65Verify'] ?? '',
            path:$streamSettings['xhttpSettings']['path'],
            host:$streamSettings['xhttpSettings']['host'],
            mode:$streamSettings['xhttpSettings']['mode'],
            network: $streamSettings['network'],
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
