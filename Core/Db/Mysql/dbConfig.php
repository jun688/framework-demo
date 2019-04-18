<?php
/**
 * Created by PhpStorm.
 * User: tony
 * Date: 2019-04-18
 * Time: 22:59
 */

namespace Core\Db\Mysql;

class dbConfig
{
    private $host = '';

    private $name = '';

    private $username = '';

    private $password = '';

    private $dsn;

    public function __construct(
        string $host, string $username, string $password, string $name
    )
    {
        $this->host = $host;
        $this->name = $name;
        $this->username = $username;
        $this->password = $password;
        $this->dsn = "mysql:dbname={$this->name};host={$this->host};";
    }

    public function __get($name = '')
    {
        return $this->$name;
    }

    public function __set($name = '', $value = '')
    {
        $this->$name = $value;
    }
}


























