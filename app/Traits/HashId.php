<?php

namespace App\Traits;

use Hashids\Hashids;

trait HashId
{
    private Hashids $hashids;

    public function hashids(): Hashids
    {
        if (!isset($this->hashids)) {
            $this->hashids = new Hashids(
                salt: config('app.key'),
                minHashLength: 6
            );
        }

        return $this->hashids;
    }
}
