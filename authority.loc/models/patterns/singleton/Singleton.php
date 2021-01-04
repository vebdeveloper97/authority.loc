<?php


namespace app\models\patterns\singleton;

use yii\db\mssql\PDO;

class Singleton
{
    private static $instance = null;
    private $connection;

    private $host = 'localhost';
    private $username = 'authority';
    private $password = '571632';
    private $dbname = 'authority';

    public function __construct()
    {
        $this->connection = new \mysqli(
            $this->host,
            $this->username,
            $this->password,
            $this->dbname
        );
        $this->connection->query("SET NAMES utf8");
    }

    public static function getInstance()
    {
        if(self::$instance === null)
        {
            self::$instance = new Singleton();
        }

        return self::$instance;
    }

    public function getConnection()
    {
        return $this->connection;
    }

    public function getQuery(){
        $sql = "SELECT * FROM about_uz";
        return $this->connection->query($sql);
    }
}