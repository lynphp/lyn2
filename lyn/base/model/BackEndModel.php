<?php

namespace lyn\base\model;

interface BackEndModel{
    public static function seedData():int;
    public static function syncTable():int;
}