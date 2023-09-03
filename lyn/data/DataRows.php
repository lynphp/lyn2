<?php

namespace lyn\data;

class DataRows extends \IteratorIterator {
    public function current():object {
        $current =  parent::current();
        return (object) $current;
    }
}