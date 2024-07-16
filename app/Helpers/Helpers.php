<?php

namespace App\Helpers;

use Carbon\Carbon;

class Helpers
{
    public static function formatDate($value): string
    {
        return Carbon::parse($value)->format('d/m/Y');
    }

    public static function formatDateHour($value): string
    {
        return Carbon::parse($value)->format('d/m/Y H:i');
    }
}
