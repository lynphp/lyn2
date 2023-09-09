<?php
namespace App\backend\models;
use App\app\models\Shoe;
use lyn\base\model\BackEndModel;

class ShoeBackend extends Shoe implements BackEndModel {


    public static function syncTable(): int
    {
        parent::defineTable([
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
        ]);
    }

    public static function seedData(): int
    {
        $instance=new Shoe();
        $data=[
            'fieldsOrder'=>'name,lastname,email',
            'records'=>[
                array('juan1','pablo4','juan.pablo5@gmail.com'),
                array('juan2','pablo3','juan.pabloe@gmail.com'),
            ]
        ];
        return $instance->seed($data);
    }
}