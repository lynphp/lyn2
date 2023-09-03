<?php


namespace App\models;

use lyn\base\Table;

class Shoe extends Table
{


    public function __construct()
    {
        $tableDef = [
            'name' => 't_shoe',
            'schemas' => [
                'mysql' => [
                    'id' => 'INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY',
                    'name' => 'VARCHAR(30) NOT NULL',
                    'lastname' => 'VARCHAR(50)',
                    'email' => 'VARCHAR(50)',
                    'updated_at' => 'TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
                    'created_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
                ]
            ]
        ];
        $this->defineTable($tableDef);
        //$this->push('mysql');
    }
    public static function getShoes()
    {
    }
}
