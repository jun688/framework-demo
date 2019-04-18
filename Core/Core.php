<?php
/**
 * Created by PhpStorm.
 * User: tony
 * Date: 2019-04-18
 * Time: 13:58
 */

namespace Core;

use Core\Exception\CoreException;

use Closure;

class Core
{
    /**
     * @var array
     */
    private $handlesList = [];

    /**
     * 请求对象
     * @var
     */
    private $request;

    /**
     * 框架跟根目录
     * @var
     */
    private $rootPath;

    /**
     * 响应对象
     * @var
     */
    private $response;

    /**
     * 实例
     * @var
     */
    public static $app;

    /**
     * 服务容器
     * @var
     */
    public static $container;

    /**
     *
     * Core constructor.
     * @param $rootPath
     * @param Closure $loader
     */
    public function __construct($rootPath, Closure $loader)
    {
        $this->rootPath = $rootPath;

        $loader();
        Load::register($this);

        self::$app = $this;
        self::$container = new Container();


    }

}