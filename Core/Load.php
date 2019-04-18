<?php
/**
 * Created by PhpStorm.
 * User: tony
 * Date: 2019-04-18
 * Time: 15:37
 * 注册加载
 */

namespace Core;

use Core\Exception\CoreException;
use Core\Core;

class Load
{
    /**
     * 类名映射
     * @var array
     */
    public static $map = [];

    /**
     * 类命名空间映射
     * @var array
     */
    public static $namespaceMap = [];

    /**
     * 启动注册
     * @param \Core\Core $app
     */
    public static function register(Core $app)
    {
        self::$namespaceMap = [
            'Core' => $app->rootPath
        ];

        //注册加载函数
        spl_autoload_register(['Core\Load', 'autoload']);

        //引入composer自加载文件
        require_once ($app->rootPath . 'vendor/autoload.php');
    }

    /**
     * 自动加载
     * @param $class
     */
    private static function autoload($class)
    {
        $classOrign = $class;
        $classInfo = explode('\\', $class);
        $className = array_pop($classInfo);
        foreach ($classInfo as &$v) {
            $v = strtolower($v);
        }
        unset($v);

        array_push($classInfo, $className);
        $class = implode('\\', $classInfo);
        $path = self::class['Core'];
        $classPath = $path . '/' . str_replace('\\', '/', $class) . '.php';

        self::$map[$classOrign] = $classPath;
        require_once ($classPath);
    }
}