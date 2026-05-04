<?php

namespace App\Services\Xui\DTO;

final class ServerStatusDTO
{
    public function __construct(
        public string  $serverName,
        public string  $serverIp,
        public string  $serverType,
        public string  $serverPort,
        public string  $serverFolder,

        public ?float  $cpu,
        public ?int    $cpuCores,
        public ?int    $logicalPro,
        public ?float  $cpuSpeedMhz,

        public ?int    $memCurrent,
        public ?int    $memTotal,

        public ?int    $swapCurrent,
        public ?int    $swapTotal,

        public ?int    $diskCurrent,
        public ?int    $diskTotal,

        public ?string $xray,
        public ?int    $uptime,
        public ?int    $tcpCount,
        public ?int    $udpCount,

        public ?int    $netIOUp,
        public ?int    $netIODown,

        public bool    $error = false,
    )
    {
    }

    public static function fromRequest($server, $response): self
    {
        if (!$response || !$response->successful()) {
            return new self(
                serverName: $server->name,
                serverIp: $server->ip,
                serverType: $server->type,
                serverPort: $server->port,
                serverFolder:$server->folder,
                cpu: null,
                cpuCores: null,
                logicalPro: null,
                cpuSpeedMhz: null,
                memCurrent: null,
                memTotal: null,
                swapCurrent: null,
                swapTotal: null,
                diskCurrent: null,
                diskTotal: null,
                xray: null,
                uptime: null,
                tcpCount: null,
                udpCount: null,
                netIOUp:null,
                netIODown:null,
                error: true
            );
        }

        $data = $response->json('obj');
        return new self(
            serverName: $server->name,
            serverIp: $server->ip,
            serverType: $server->type,
            serverPort: $server->port,
            serverFolder:$server->folder,

            cpu: round($data['cpu'] ?? 0, 2),
            cpuCores: $data['cpuCores'] ?? null,
            logicalPro: $data['logicalPro'] ?? null,
            cpuSpeedMhz: $data['cpuSpeedMhz'] ?? null,

            memCurrent: $data['mem']['current'] ?? null,
            memTotal: $data['mem']['total'] ?? null,

            swapCurrent: $data['swap']['current'] ?? null,
            swapTotal: $data['swap']['total'] ?? null,

            diskCurrent: $data['disk']['current'] ?? null,
            diskTotal: $data['disk']['total'] ?? null,

            xray: ($data['xray']['state'] ?? '') . ' ' .
            ($data['xray']['errorMsg'] ?? '') . ' ' .
            ($data['xray']['version'] ?? ''),

            uptime: $data['uptime'] ?? null,
            tcpCount: $data['tcpCount'] ?? null,
            udpCount: $data['udpCount'] ?? null,

            netIOUp:$data['netIO']['up'] ?? null,
            netIODown:$data['netIO']['down'] ?? null,
        );
    }

    protected function bytesFormated($bytes): string
    {
        if ($bytes <= 0) return '0 B';

        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $i = floor(log($bytes, 1024));

        return round($bytes / (1024 ** $i), 2) . ' ' . $units[$i];
    }

    private function procentFormated($current,$total)
    {
        if (!$total) return null;

        return round($current / $total * 100, 2);
    }
    public function memoryPercentFormated(): ?float
    {
       return $this->procentFormated($this->memCurrent,$this->memTotal);
    }
    public function diskPercentFormated(): ?float
    {
       return $this->procentFormated($this->diskCurrent,$this->diskTotal);
    }

    public function memTotalFormated(): string
    {
        return $this->bytesFormated($this->memTotal);
    }
    public function memCurrentFormated(): string
    {
        return $this->bytesFormated($this->memCurrent);
    }

    public function diskCurrentFormated()
    {
        return $this->bytesFormated($this->diskCurrent);
    }
    public function diskTotalFormated()
    {
        return $this->bytesFormated($this->diskTotal);
    }

    public function uptime()
    {
        $h = floor($this->uptime / 3600);
        $m = floor(($this->uptime % 3600) / 60);

        return "{$h}h {$m}m";
    }

    public function serverLink()
    {
        return $this->serverType.'://'.$this->serverIp.':'.$this->serverPort.'/'.$this->serverFolder;
    }
}
