<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait HasHashId
{
    use HashId;

    protected function hashId(): Attribute
    {
        return Attribute::make(
            get: fn(mixed $value, array $attributes) => $this->hashids->encode($attributes['id'])
        );
    }
}
