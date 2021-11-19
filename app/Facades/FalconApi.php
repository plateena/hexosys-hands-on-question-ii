<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;


/**
 * Class FalconApi
 * @author yourname
 */
class FalconApi extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'falcon-api';
    }
}
