<?php

namespace App\DAO\Mysql;

abstract class Connection
{
    /**
     * @var \PDO
     */
    protected $pdo;
    
    public function __construct(){
        
    }
    
    public function db() {
        
        $type = getenv('MYSQL_DB_CONNECTION');
        $host = getenv('MYSQL_DB_HOST');
        $port = getenv('MYSQL_DB_PORT');
        $user = getenv('MYSQL_DB_USERNAME');
        $pass = getenv('MYSQL_DB_PASSWORD');
        $dbname = getenv('MYSQL_DB_DATABASE');
    
        $dsn = "{$type}:dbname={$dbname};host={$host};port={$port}";
        $this->pdo = new \PDO($dsn, $user, $pass);
        $this->pdo->exec("set names utf8");
        $this->pdo->setAttribute(
            \PDO::ATTR_ERRMODE,
            \PDO::ERRMODE_EXCEPTION
        );

    }

    public function dbS(){

        $type = getenv('MYSQLS_DB_CONNECTION');
        $host = getenv('MYSQLS_DB_HOST');
        $port = getenv('MYSQLS_DB_PORT');
        $user = getenv('MYSQLS_DB_USERNAME');
        $pass = getenv('MYSQLS_DB_PASSWORD');
        $dbname = getenv('MYSQLS_DB_DATABASE');
    
        $dsn = "{$type}:dbname={$dbname};host={$host};port={$port}";
        $this->pdo = new \PDO($dsn, $user, $pass);
        $this->pdo->exec("set names utf8");
        $this->pdo->setAttribute(
            \PDO::ATTR_ERRMODE,
            \PDO::ERRMODE_EXCEPTION
        );

    }

    public function dbW(){

        $type = getenv('MYSQLW_DB_CONNECTION');
        $host = getenv('MYSQLW_DB_HOST');
        $port = getenv('MYSQLW_DB_PORT');
        $user = getenv('MYSQLW_DB_USERNAME');
        $pass = getenv('MYSQLW_DB_PASSWORD');
        $dbname = getenv('MYSQLW_DB_DATABASE');
    
        $dsn = "{$type}:dbname={$dbname};host={$host};port={$port}";
        $this->pdo = new \PDO($dsn, $user, $pass);
        $this->pdo->exec("set names utf8");
        $this->pdo->setAttribute(
            \PDO::ATTR_ERRMODE,
            \PDO::ERRMODE_EXCEPTION
        );

    }
}