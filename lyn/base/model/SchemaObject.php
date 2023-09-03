<?php

namespace lyn\base\model;

use lyn\base\connections\Database;
use lyn\base\model\Field;
use lyn\data\DataRows;

abstract class SchemaObject extends Database
{
    public array $fields = [];
    public string $primaryKey = '';
    public array $schemas = [];
    public string $tableName = '';
    abstract public function seed(array $data=[]):int;
    abstract public function sync():int;
    final public function getCSVFieldNames():string{
        $csvField='';
        foreach($this->fields as $field){
            $csvField.="`{$field->name}`,";
        }
        return rtrim($csvField,',');
    }
    /**
     * @param $tableDef
     * @return void
     */
    final public function defineTable($tableDef): void
    {
        $this->tableName = $tableDef['name'];
        $this->schemas = $tableDef['schemas'];
        foreach ($tableDef['schemas'] as $db => $fields) {
            foreach ($fields as $key => $field) {
                $fld = new Field($key);
                if (str_contains($field, 'PRIMARY KEY')) {
                    $this->primaryKey = $key;
                }
                if (str_contains($field, 'UNSIGNED')) {
                    $fld->min = 0;
                }
                if (str_contains($field, 'INT')) {
                    $fld->type = 'number';
                }

                if (str_contains($field, 'NOT NULL')) {
                    $fld->required = true;
                }
                if (str_contains($field, 'VARCHAR')) {
                    $fld->type = 'string';
                    $pattern = "/\\d+/";
                    preg_match($pattern, $field, $matches);
                    $fld->max = (int)$matches[0];
                }
                $fields[$key] = $fld;
            }
        }
        $tableExist = $this->checkTableExist();
        if ($tableExist->rowCount() === 0) {
            $this->push('mysql');
        }
        $tableDetails = $this->getTableDetails();
        $table_fields = $tableDetails->fetchAll(\PDO::FETCH_ASSOC);
    }
    private function checkTableExist():bool|\PDOStatement
    {
        $tblName = $this->tableName;
        $sql = "SHOW TABLES LIKE '$tblName'";
        return $this->query($sql);
    }
    private function getTableDetails():bool|\PDOStatement
    {
        $tblName = $this->tableName;
        $sql = "DESCRIBE $tblName;";
        return $this->query($sql);
    }
    /**
     * Drop the table and
     */
    final public function push($schema): false|int
    {
        $this->schemas[$schema];
        $command = "CREATE TABLE " . $this->tableName . " (\r";
        foreach ($this->schemas[$schema] as $key => $fieldSetup) {
            $command .= " " . $key . " " . $fieldSetup . ",\r";
        }
        $command = substr($command, 0, strlen($command) - 2);
        $command .= ")";
        $this->execute($command);
    }

    /**
     * @param array $filter
     * @return array
     */
    final public function findAll($filter=[]):DataRows{
        //$fields = $filter['fields'];
        //$where = $filter['where'];
        $conn = $this->getQueryConnection();
        $stmt = $conn->prepare("SELECT * FROM {$this->tableName}");
        $stmt->execute();
        $this->results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return new DataRows(new \ArrayIterator($this->results));
    }
}
