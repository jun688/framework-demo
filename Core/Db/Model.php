<?php
/**
 * Created by PhpStorm.
 * User: tony
 * Date: 2019-04-18
 * Time: 22:14
 */

namespace Core\Db;

use Core\Core;
use Core\Exception\CoreException;

class Model extends Entity
{
    public function __construct()
    {
        parent::__construct();
        $this->getTableName();
    }


    public function getTableName()
    {
        $prefix = Core::$container->getSingle('config')
            ->config['database']['prefix'];
        $callClassName = get_called_class();
        $callClassName = explode('\\', $callClassName);
        $callClassName = array_pop($callClassName);
        if (! empty($this->tableName)) {
            if (empty($prefix)) {
                return;
            }
            $this->tableName = $prefix . '_' . $this->tableName;
            return;
        }
        preg_match_all('/([A-Z][a-z]*)/', $callClassName, $match);
        if (! isset($match[1][0]) || empty($match[1][0])) {
            throw new CoreException('model name invalid', 401);
        }
        $match = $match[1];
        $count = count($match);
        if ($count === 1) {
            $this->tableName = strtolower($match[0]);
            if (empty($prefix)) {
                return;
            }
            $this->tableName = $prefix . '_' . $this->tableName;
            return;
        }
        $last = strtolower(array_pop($match));
        foreach ($match as $v) {
            $this->tableName .= strtolower($v) . '_';
        }
        $this->tableName .= $last;
        if (empty($prefix)) {
            return;
        }
        $this->tableName = $prefix . '_' . $this->tableName;
    }
}