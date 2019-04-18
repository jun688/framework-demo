<?php
/**
 * Created by PhpStorm.
 * User: tony
 * Date: 2019-04-18
 * Time: 23:07
 */

namespace Core\Db\Mysql;

use Core\Db\Entity;
use Core\Db\Mysql\dbConfig;
use PDO;

class Db
{
    private $conf;

    private $pdo;

    private $pdoStatement;

    public function __construct(dbConfig $conf)
    {
        $this->conf = $conf;
        $this->getConnect();
    }

    public function getConnect()
    {
        $this->pdo = new PDO(
            $this->conf->getDsn(),
            $this->conf->getUsername(),
            $this->conf->getPassword()
        );
    }

    public function findOne(Entity $db)
    {
        $this->pdoStatement = $this->pdo->prepare($db->sql);
        $this->bindValue($db);
        $this->pdoStatement->execute();
        return $this->pdoStatement->fetch(PDO::FETCH_ASSOC);
    }

    public function findAll(DB $db)
    {
        $this->pdoStatement = $this->pdo->prepare($db->sql);
        $this->bindValue($db);
        $this->pdoStatement->execute();
        return $this->pdoStatement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function save(DB $db)
    {
        $this->pdoStatement = $this->pdo->prepare($db->sql);
        $this->bindValue($db);
        $res = $this->pdoStatement->execute();
        if (! $res) {
            return false;
        }
        return $db->id  = $this->pdo->lastInsertId();
    }

    public function delete(DB $db)
    {
        $this->pdoStatement = $this->pdo->prepare($db->sql);
        $this->bindValue($db);
        $this->pdoStatement->execute();
        return $this->pdoStatement->rowCount();
    }

    public function update(DB $db)
    {
        $this->pdoStatement = $this->pdo->prepare($db->sql);
        $this->bindValue($db);
        return $this->pdoStatement->execute();
    }

    public function query(DB $db)
    {
        $res = [];
        foreach ($this->pdo->query($db->sql, PDO::FETCH_ASSOC) as $v) {
            $res[] = $v;
        }
        return $res;
    }

    public function bindValue(DB $db)
    {
        if (empty($db->params)) {
            return;
        }
        foreach ($db->params as $k => $v) {
            $this->pdoStatement->bindValue(":{$k}", $v);
        }
    }



}





















