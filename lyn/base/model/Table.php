<?php


namespace lyn\base\model;

use lyn\base\model\SchemaObject;

class Table extends SchemaObject
{
    public function __construct()
    {
    }

    final public function seed(array $data=[]): int
    {
         $count = 0;
         $conn = $this->getExecConnection();
        foreach($data['records'] as $record){
            $placeHolder = $this->convertCSVToSQLPlaceHolders($data['fieldsOrder']);
            $stmt = $conn->prepare("INSERT INTO `{$this->tableName}` ({$data['fieldsOrder']}) VALUES ({$this->convertCSVToSQLPlaceHolders($data['fieldsOrder'])})");
            $result = $stmt->execute($record);
            if($result){
                $count++;
            }
        }
        return $count;
    }

    /**
     * Converts the comma separated values into comma separated question marks ("?")
     * @param $csvFieldNames String The comma separated values
     * @return string The comma separated question marks ("?")
     */
    private function convertCSVToSQLPlaceHolders($csvFieldNames):string{
        $csvPlaceHolders = '';
        foreach(explode(',',$csvFieldNames) as $str){
            $csvPlaceHolders.='?,';
        }
        return rtrim($csvPlaceHolders,',');
    }
    final public function sync(): int
    {
        // TODO: Implement sync() method.
    }
}
