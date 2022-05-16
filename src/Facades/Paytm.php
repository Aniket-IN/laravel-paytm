<?php

namespace AniketIN\Paytm\Facades;

use AniketIN\Paytm\Paytm as PaytmPaytm;
use Illuminate\Support\Facades\Facade;

/**
 * @see \AniketIN\Paytm\Paytm
 */
class Paytm extends Facade
{
    protected static function getFacadeAccessor()
    {
        return PaytmPaytm::class;
    }
}
