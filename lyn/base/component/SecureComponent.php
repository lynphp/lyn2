<?php

namespace lyn\base\component;


abstract class SecureComponent extends Component implements SecureComponentInterface
{
    public function __construct()
    {
    }
    public abstract function secure();
}
