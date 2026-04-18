<?php
function formatBytes($bytes): string
{
    if ($bytes <= 0) return '0 B';

    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $i = floor(log($bytes, 1024));

    return round($bytes / (1024 ** $i), 2) . ' ' . $units[$i];
}

function formatUptime($seconds): string
{
    $h = floor($seconds / 3600);
    $m = floor(($seconds % 3600) / 60);

    return "{$h}h {$m}m";
}