<?php

namespace App\Traits;

use App\Models\Scopes\ActiveScope;

trait Active
{
    public static function bootActive(): void
    {
        static::addGlobalScope(new ActiveScope);
    }
}
