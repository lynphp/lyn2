<?php


namespace App\models;

use lyn\base\model\Table;

class Shoe extends Table
{
    public string $tableName = 't_shoe';

    public function getShoes(){
        return $this->findAll();
    }
}
