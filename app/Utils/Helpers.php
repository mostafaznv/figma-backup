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

if (!function_exists('pascalCase')) {
    /**
     * Convert strings to pascal case
     *
     * @param string $str
     * @return string
     */
    function pascalCase(string $str): string
    {
        $str = str_replace(['–', '-', '_', '(', ')', '/'], ' ', $str);

        return \Illuminate\Support\Str::of($str)->camel()->ucfirst();
    }
}

if (!function_exists('titleCase')) {
    /**
     * Convert strings to title case
     *
     * @param string $str
     * @return string
     */
    function titleCase(string $str): string
    {
        $str = str_replace(['–', '-', '_', '(', ')', '/'], ' ', $str);

        return \Illuminate\Support\Str::of($str)->title();
    }
}

if (!function_exists('enumToNames')) {
    /**
     * Returns an array of enum names
     *
     * @param UnitEnum[] $enums
     * @return array
     */
    function enumToNames(array $enums): array
    {
        return array_column($enums, 'name');
    }
}
