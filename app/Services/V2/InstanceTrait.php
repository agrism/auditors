<?php

namespace App\Services\V2;

trait InstanceTrait
{
    protected static ?self $instance = null;

    public static function instance(): self
    {
        return self::$instance ?: self::$instance = new self;
    }
}
