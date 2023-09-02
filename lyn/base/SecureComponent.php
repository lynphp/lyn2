<?php

namespace lyn\base;

use lyn\base\SecureComponentInterface;

abstract class SecureComponent extends Component implements SecureComponentInterface
{
    public function __construct()
    {
    }
    public abstract function secure();
}
