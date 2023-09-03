<?php

namespace lyn\base\model;

use lyn\base\connections\Database;

class SchemaObject extends Database
{
    public $fields = [];
    public $primaryKey = '';
    public $schemas = [];
    public $tableName = '';
    public function seed()
    {
    }
    public function sync()
    {
    }
    public function defineTable($tableDef)
    {
        $this->tableName = $tableDef['name'];
        $this->schemas = $tableDef['schemas'];
        foreach ($tableDef['schemas'] as $db => $fields) {
            foreach ($fields as $key => $field) {
                $fld = new Field($key);
                if (strpos($field, 'PRIMARY KEY') !== false) {
                    $this->primaryKey = $key;
                }
                $pos = strpos($field, 'UNSIGNED');
                if (strpos($field, 'UNSIGNED') !== false) {
                    $fld->min = 0;
                }
                if (strpos($field, 'INT') !== false) {
                    $fld->type = 'number';
                }

                if (strpos($field, 'NOT NULL') !== false) {
                    $fld->required = true;
                }
                $pos = strpos($field, 'VARCHAR');
                if (strpos($field, 'VARCHAR') !== false) {
                    $fld->type = 'string';
                    $pattern = "/\\d+/";
                    preg_match($pattern, $field, $matches);
                    $fld->max = (int)$matches[0];
                }
                $fields[$key] = $fld;
            }
        }
        $tableExist = $this->checkTableExist();
        $count = $tableExist->rowCount();
        if ($count == 0) {
            $this->push('mysql');
        }
        $tableDetails = $this->getTableDatails();
        $table_fields = $tableDetails->fetchAll(\PDO::FETCH_ASSOC);
        var_dump($table_fields);
    }
    private function checkTableExist()
    {
        $tblName = $this->tableName;
        $sql = "SHOW TABLES LIKE '$tblName'";
        return parent::query($sql);
    }
    private function getTableDatails()
    {
        $tblName = $this->tableName;
        $sql = "DESCRIBE $tblName;";
        return parent::query($sql);
    }
    /**
     * Drop the table and
     */
    public function push($schema)
    {
        $this->schemas[$schema];
        $command = "CREATE TABLE " . $this->tableName . " (\r";
        foreach ($this->schemas[$schema] as $key => $fieldSetup) {
            $command = $command . " " . $key . " " . $fieldSetup . ",\r";
        }
        $command = substr($command, 0, strlen($command) - 2);
        $command = $command . ")";
        parent::execute($command);
    }
}
