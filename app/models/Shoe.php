<?php


namespace App\app\models;

use lyn\base\model\Table;
use lyn\data\DataRows;

class Shoe extends Table
{
    public string $tableName = 't_shoe';

    final public function getShoes():array|DataRows{
        return $this->findAll();
    }

    final public function index():string
    {
    }
}
