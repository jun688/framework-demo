<?php
/**
 * Created by PhpStorm.
 * User: tony
 * Date: 2019-04-18
 * Time: 22:45
 */

namespace Core\Db;

use Core\Exception\CoreException;

trait Interpreter
{
    private $where = [];

    public $params = [];

    private $orderBy = '';

    private $limit = '';

    private $sql = '';

    public function insert(array $data)
    {
        if (empty($data)) {
            throw new CoreException("argument data is null", 400);
        }
        $this->params = NULL;
        $fieldString = '';
        $valueString = '';
        $i = 0;
        foreach ($data as $k => $v) {
            if ($i === 0) {
                $fieldString .= "`{$k}`";
                $valueString .= ":{$k}";
                $this->params[$k] = $v;
                ++$i;
                continue;
            }
            $fieldString .= " , `{$k}`";
            $valueString .= " , :{$k}";
            $this->params[$k] = $v;
            ++$i;
        }
        unset($k);
        unset($v);
        $this->sql = "INSERT INTO `{$this->tableName}` ({$fieldString}) VALUES ({$valueString})";
    }

    public function del()
    {
        $this->sql = "DELETE FROM `{$this->tableName}`";
    }

    public function updateData(array $data)
    {
        if (empty($data)) {
            throw new CoreException("argument data is null", 400);
        }
        $set = '';
        $dataCopy = $data;
        $pop = array_pop($dataCopy);
        foreach ($data as $k => $v) {
            if ($v === $pop) {
                $set .= "`{$k}` = :$k";
                $this->params[$k] = $v;
                continue;
            }
            $set .= "`{$k}` = :$k,";
            $this->params[$k] = $v;
        }
        $this->sql = "UPDATE `{$this->tableName}` SET {$set}";
    }

    public function select(array $data)
    {
        $field = '';
        $count = count($data);
        switch ($count) {
            case 0:
                $field = '*';
                break;
            case 1:
                if(! isset($data[0])) {
                    throw new CoreException(
                        "data format invalid",
                        400
                    );
                }
                $field = "`{$data[0]}`";
                break;
            default:
                $last = array_pop($data);
                foreach ($data as $v) {
                    $field .= "{$v},";
                }
                $field .= $last;
                break;
        }
        $this->sql = "SELECT $field FROM `{$this->tableName}`";
    }

    public function where(array $data)
    {
        if (empty($data)) {
            return;
        }
        $this->params = NULL;
        $count = count($data);
        // 单条件
        if ($count === 1) {
            $field = array_keys($data)[0];
            $value = array_values($data)[0];
            if (! is_array($value)){
                $this->where  = " WHERE `{$field}` = :{$field}";
                $this->params = $data;
                return $this;
            }
            $this->where = " WHERE `{$field}` {$value[0]} :{$field}";
            $this->params[$field] = $value[1];
            return $this;
        }
        //多条件
        $tmp  = $data;
        $last = array_pop($tmp);
        foreach ($data as $k => $v) {
            if ($v === $last) {
                if (! is_array($v)){
                    $this->where .= "`{$k}` = :{$k}";
                    $this->params[$k] = $v;
                    continue;
                }
                $this->where .= "`{$k}` {$v[0]} :{$k}";
                $this->params[$k] = $v[1];
                continue;
            }
            if (! is_array($v)){
                $this->where  .= " WHERE `{$k}` = :{$k} AND ";
                $this->params[$k] = $v;
                continue;
            }
            $this->where .= " WHERE `{$k}` {$v[0]} :{$k} AND ";
            $this->params[$k] = $v[1];
            continue;
        }
        return $this;
    }

    public function orderBy($sort)
    {
        if (! is_string($sort)) {
            throw new CoreException(
                'argv is not string',
                400
            );
        }
        $this->orderBy = " order by {$sort}";
        return $this;
    }

    public function limit($start = 0, $len = 0)
    {
        if (! is_numeric($start) || (! is_numeric($len))) {
            throw new CoreException(400);
        }
        if ($len === 0) {
            $this->limit = " limit {$start}";
            return $this;
        }
        $this->limit = " limit {$start},{$len}";
        return $this;
    }

    public function querySql($sql = '')
    {
        if (empty($sql)) {
            throw new CoreException("sql is empty", 400);
        }
        $this->sql = $sql;
    }
}