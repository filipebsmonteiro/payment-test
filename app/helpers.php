<?php

declare(strict_types=1);

use Illuminate\Support\Carbon;

if (!function_exists('isNotNull')) {

    function isNotNull($val): bool
    {
        return !is_null($val);
    }
}

if (!function_exists('isNull')) {

    function isNull($val): bool
    {
        return is_null($val);
    }
}

if (!function_exists('issetAndNotNullNotEmpty')) {

    function issetAndNotNullNotEmpty($val): bool
    {
        return isset($val) && !is_null($val) && !empty($val);
    }
}

if (!function_exists('subMinutes')) {

    function subMinutes(int $minutes): Carbon
    {
        return Carbon::now()->subMinutes($minutes);
    }
}
