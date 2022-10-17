<?php

namespace LemurEngine\LemurBot\Facades;

use Illuminate\Support\Facades\Facade;


class LemurPriv extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'lemur-privileges';
    }
}
