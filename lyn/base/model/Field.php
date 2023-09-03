<?php

namespace lyn\base\model;

class Field
{
    public string $name;
    public string $type;
    public int $max;
    public int $min;
    public bool $required;
    public function __construct($name)
    {
        $this->name = $name;
    }
}
