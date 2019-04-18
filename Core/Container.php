<?php
/**
 * Created by PhpStorm.
 * User: tony
 * Date: 2019-04-18
 * Time: 15:11
 */

namespace Core;

use Core\Exception\CoreException;

class Container
{
    private $classMap = [];

    public $instanceMap = [];

    /**
     * 注入类
     * @param string $alias 类别名
     * @param string $objectName 类名
     * @return mixed
     */
    public function set($alias = '', $objectName = '')
    {
        $this->classMap[$alias] = $objectName;
        if (is_callable($objectName)) {
            return $objectName();
        }

        return new $objectName;
    }

    /**
     * 获取类实例
     * @param string $alias
     * @return mixed
     * @throws CoreException
     */
    public function get($alias = '')
    {
        if (array_key_exists($alias, $this->classMap)) {
            if (is_callable($this->classMap[$alias])) {
                return $this->classMap[$alias]();
            }

            if (is_object($this->classMap[$alias])) {
                return $this->classMap[$alias];
            }

            return new $this->classMap[$alias];
        }

        throw new CoreException(404, 'Class: ' . $alias);
    }


    /**
     * 注入单例类
     * @param string $alias
     * @param string $objectName
     * @return mixed
     * @throws CoreException
     */
    public function setSingle($alias = '', $objectName = '')
    {
        if (is_callable($alias)) {
            $instance = $alias();
            $className = get_class($instance);
            $this->instanceMap[$className] = $instance;
            return $instance;
        }

        if (is_callable($objectName)) {
            if (empty($alias)) {
                throw new CoreException(400, "{$alias} is empty");
            }

            if (array_key_exists($alias, $this->instanceMap)) {
                return $this->instanceMap[$alias];
            }

            $this->instanceMap[$alias] = $objectName;
        }

        if (is_object($objectName)) {
            $className = get_class($alias);
            if (array_key_exists($className, $this->instanceMap)) {
                return $this->instanceMap[$alias];
            }

            $this->instanceMap[$className] = $alias;
            return $this->instanceMap[$className];
        }

        if (is_object($objectName)) {
            if (empty($alias)) {
                throw new CoreException(400, "{$alias} is empty");
            }

            $this->instanceMap[$alias]= $objectName;
            return $this->instanceMap[$alias];
        }

        if (empty($alias) && empty($objectName)) {
            throw new CoreException(400, "{$alias} and {$objectName} is empty");
        }

        $this->instanceMap[$alias] = new $alias();
        return $this->instanceMap[$alias];
    }

    /**
     * 获取单例类
     * @param string $alias
     * @param string $closure
     * @return mixed
     * @throws CoreException
     */
    public function getSingle($alias = '', $closure = '')
    {
        if (array_key_exists($alias, $this->instanceMap)) {
            $instance = $this->instanceMap[$alias];

            if (is_callable($instance)) {
                return $this->instanceMap[$alias] = $instance();
            }

            return $instance;
        }

        if (is_callable($closure)) {
            return $this->instanceMap[$alias] = $closure();
        }

        throw new CoreException(404, 'Class: ' . $alias);
    }

}