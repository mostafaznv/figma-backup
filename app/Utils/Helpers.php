<?php

if (!function_exists('byteToMb')) {
    /**
     * Convert Bytes to Megabytes
     *
     * @param int $bytes
     * @return int
     */
    function byteToMb(int $bytes): int
    {
        if ($bytes) {
            return round($bytes / config('settings.bytes-in-mb'), 2);
        }

        return $bytes;
    }
}

if (!function_exists('byteToKb')) {
    /**
     * Convert Bytes to Kilobytes
     *
     * @param int $bytes
     * @return int
     */
    function byteToKb(int $bytes): int
    {
        if ($bytes) {
            return round($bytes / config('settings.bytes-in-kb'), 2);
        }

        return $bytes;
    }
}
