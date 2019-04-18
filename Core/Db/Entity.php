<?php
/**
 * Created by PhpStorm.
 * User: tony
 * Date: 2019-04-18
 * Time: 22:16
 */

namespace Core\Db;

use Core\Core;
use Core\Exception\CoreException;

class Entity
{
    use Interpreter;

    protected $dbType = '';

    protected $tableName = '';

    private $dbConfig = [
       'host' => '',
       'name' => '',
       'username' => '',
       'password' => ''
    ];

    protected $dbMap = [
        'mysqldb' => 'Core\Db\dbConfig'
    ];

    protected $dbInstance;

    protected $id = '';

    public static function table($tableName = '')
    {
        $db = new self();
        $db->tableName = $tableName;
        $prefix = Core::$container->getSingle('config')->config['database']['prefix'];
        if (!empty($prefix)) {
            $db->tableName = $prefix . '_' . $db->tableName;
        }

        return $db;
    }


    public function init()
    {
        $config = Core::$container->getSingle('config');
        $this->dbType = $config->config['database']['type'];

        $this->initDb();
        $this->decide();
    }

    public function decide()
    {
        $dbName = $this->dbMap[$this->dbType];
        $dbConfig = $this->dbConfig;
        $this->dbInstance = Core::$container->getSingle("{$this->dbType}",
            function () use ($dbName, $dbConfig) {
                return new $dbName(
                    $dbConfig['host'],
                    $dbConfig['name'],
                    $dbConfig['username'],
                    $dbConfig['password']
                );
            });
    }

    public function initDb()
    {
        $config = Core::$container->getSingle('config');
        $dbConfig = $config->config['database'];
        $this->dbConfig['host'] = $dbConfig['host'];
        $this->dbConfig['name'] = $dbConfig['name'];
        $this->dbConfig['username'] = $dbConfig['username'];
        $this->dbConfig['password'] = $dbConfig['password'];
    }

    public function findOne(array $data)
    {
        $this->select($data);
        $this->buildSql();
        $functionName = __FUNCTION__;
        return $this->dbInstance->$functionName($this);
    }

    public function findAll(array $data)
    {
        $this->select($data);
        $this->buildSql();
        $functionName = __FUNCTION__;
        return $this->dbInstance->$functionName($this);
    }

    public function save(array $data)
    {
        $this->insert($data);
        $this->init();
        $functionName = __FUNCTION__;
        return $this->dbInstance->$functionName($this);
    }

    public function delete()
    {
        $this->del();
        $this->buildSql();
        $functionName = __FUNCTION__;
        return $this->dbInstance->$functionName($this);
    }

    public function update($data = [])
    {
        $this->updateData($data);
        $this->buildSql();
        $functionName = __FUNCTION__;
        return $this->dbInstance->$functionName($this);
    }

    public function query($sql = '')
    {
        $this->querySql($sql);
        $this->init();
        return $this->dbInstance->query($this);
    }

    public function buildSql()
    {
        if (! empty($this->where)) {
            $this->sql .= $this->where;
        }
        if (! empty($this->orderBy)) {
            $this->sql .= $this->orderBy;
        }
        if (! empty($this->limit)) {
            $this->sql .= $this->limit;
        }
        $this->init();
    }




}



























