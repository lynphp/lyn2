<?php

namespace lyn\base;

use lyn\helpers\Config;

class Database
{
    private static $execute = [];
    private static $query = [];
    public function getExecConnection()
    {
        if (sizeof(self::$execute) === 0) {
            foreach (Config::$config['db'] as $drivers) {
                foreach ($drivers as $verb => $connections) {
                    if ($verb === 'execute') {
                        foreach ($connections as $id => $dbconn) {
                            $hostName = $dbconn['hostname'];
                            $schema = $dbconn['schema'];
                            $username = $dbconn['username'];
                            $password = $dbconn['password'];
                            $port = $dbconn['port'];
                            try {
                                $conn = new \PDO("mysql:host=$hostName;port=$port;dbname=$schema", $username, $password);
                                $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                                //if ($verb === 'query') {
                                self::$execute[$id] = $conn;
                                //}
                                return self::$execute[$id];
                            } catch (\PDOException $e) {
                                echo "Connection failed: " . $e->getMessage();
                            }
                        }
                    }
                }
            }
        }

        return self::$execute[0];
    }
    public function getQueryConnection()
    {
        if (sizeof(self::$execute) === 0) {
            foreach (Config::$config['db'] as $drivers) {
                foreach ($drivers as $verb => $connections) {
                    if ($verb === 'query') {
                        foreach ($connections as $id => $dbconn) {
                            $hostName = $dbconn['hostname'];
                            $schema = $dbconn['schema'];
                            $username = $dbconn['username'];
                            $password = $dbconn['password'];
                            $port = $dbconn['port'];
                            try {
                                $conn = new \PDO("mysql:host=$hostName;port=$port;dbname=$schema", $username, $password);
                                $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                                //if ($verb === 'query') {
                                self::$query[$id] = $conn;
                                //}
                                return self::$query[$id];
                            } catch (\PDOException $e) {
                                echo "Connection failed: " . $e->getMessage();
                            }
                        }
                    }
                }
            }
        }

        return self::$query[0];
    }
    public function execute($sql)
    {
        $execConn = $this->getExecConnection();
        try {
            return $execConn->exec($sql);
        } catch (\PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }
    public function query($sql)
    {
        $execConn = $this->getQueryConnection();
        try {
            return $execConn->query($sql, \PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }
}
