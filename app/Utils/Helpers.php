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

if (!function_exists('normalizeText')) {
    /**
     * Normalize Text
     * remove unexpected characters from strings
     *
     * @param string $str
     * @return string
     */
    function normalizeText(string $str): string
    {
        $str = trim($str);
        $str = str_replace(['–', '-', '_', '(', ')', '/'], ' ', $str);

        return preg_replace('!\s+!', ' ', $str);
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
        $str = normalizeText($str);

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
        $str = normalizeText($str);

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

if (!function_exists('maskSensitiveData')) {
    /**
     * Mask sensitive data
     *
     * @param string $content
     * @return string
     */
    function maskSensitiveData(string $content): string
    {
        $figma = config('services.figma');
        $figma = array_values($figma);

        $telegram = config('services.telegram-bot-api');
        $telegram = array_values($telegram);

        $sensitiveValues = array_merge($figma, $telegram);

        return str_replace($sensitiveValues, '***', $content);
    }
}
