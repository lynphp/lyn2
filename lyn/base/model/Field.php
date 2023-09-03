<?php

namespace lyn\base;

class Field
{
    public $name;
    public $type;
    public $max;
    public $min;
    public $required;
    public function __construct($name)
    {
        $this->name = $name;
    }
}
