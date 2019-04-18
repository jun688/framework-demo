<?php
/**
 * Created by PhpStorm.
 * User: tony
 * Date: 2019-04-18
 * Time: 17:57
 */

namespace Core;

use Core\Core;
use Closure;
use Core\Exception\CoreException;


class Route
{
    /**
     * 框架实例
     *
     * the framework instance
     *
     * @var App
     */
    private $app;
    /**
     * 配置实例
     *
     * the config instance
     *
     * @var
     */
    private $config;
    /**
     * 请求对象实例
     *
     * the request instance
     *
     * @var
     */
    private $request;
    /**
     * 默认模块.
     *
     * default module
     *
     * @var string
     */
    private $moduleName = '';
    /**
     * 默认控制器
     *
     * default controller
     *
     * @var string
     */
    private $controllerName = '';
    /**
     * 默认操作.
     *
     * default action
     *
     * @var string
     */
    private $actionName = '';
    /**
     * 类文件路径.
     *
     * class path
     *
     * @var string
     */
    private $classPath = '';
    /**
     * 类文件执行类型.
     *
     * ececute type
     *
     * @var string
     */
    private $executeType = 'controller';
    /**
     * 请求uri.
     *
     * the request uri
     *
     * @var string
     */
    private $requestUri = '';
    /**
     * 路由策略.
     *
     * the current router strategy
     *
     * @var string
     */
    private $routeStrategy = '';

    /**
     * 魔法函数__get.
     *
     * @param string $name 属性名称
     *
     * @return mixed
     */
    public function __get($name = '')
    {
        return $this->$name;
    }
    /**
     * 魔法函数__set.
     *
     * @param string $name  属性名称
     * @param mixed  $value 属性值
     *
     * @return mixed
     */
    public function __set($name = '', $value = '')
    {
        $this->$name = $value;
    }
    /**
     * 注册路由处理机制.
     *
     * @param App $app 框架实例
     * @param void
     */
    public function init(Core $app)
    {
        // 注入当前对象到容器中
        $app::$container->set('router', $this);
        // request uri
        $this->request        = $app::$container->get('request');
        $this->requestUri     = $this->request->server('REQUEST_URI');
        // App
        $this->app            = $app;
        // 获取配置 get config
        $this->config         = $app::$container->getSingle('config');
        // 设置默认模块 set default module
        $this->moduleName     = $this->config->config['route']['default_module'];
        // 设置默认控制器 set default controller
        $this->controllerName = $this->config->config['route']['default_controller'];
        // 设置默认操作 set default action
        $this->actionName     = $this->config->config['route']['default_action'];
        // 路由决策 judge the router strategy
        $this->strategyJudge();

        $this->makeClassPath($this);

        // 启动路由
        $this->start();
    }
    /**
     * 路由策略决策
     *
     * @param void
     */
    public function strategyJudge()
    {
        // 路由策略
        if (! empty($this->routeStrategy)) {
            return;
        }

        // 普通路由
        if (strpos($this->requestUri, 'index.php')) {
            $this->routeStrategy = 'general';
            return;
        }
        $this->routeStrategy = 'pathinfo';
    }
    /**
     * get class path
     *
     * @return void
     */
    public function makeClassPath()
    {
        // 获取控制器类
        $controllerName    = ucfirst($this->controllerName);
        $folderName        = ucfirst($this->config->config['application_folder_name']);
        $this->classPath   = "{$folderName}\\{$this->moduleName}\\Controllers\\{$controllerName}";
    }
    /**
     * 路由机制
     *
     * @param void
     */
    public function start()
    {
        // 判断模块存不存在
        if (! in_array(strtolower($this->moduleName), $this->config->config['module'])) {
            throw new CoreException(404, 'Module:'.$this->moduleName);
        }
        // 判断控制器存不存在
        if (! class_exists($this->classPath)) {
            throw new CoreException(404, "{$this->executeType}:{$this->classPath}");
        }

        // 实例化当前控制器
        $controller = new $this->classPath();
        if (! is_callable([$controller, $this->actionName])) {
            throw new CoreException(404, 'Action:'.$this->actionName);
        }
        // 调用操作
        $actionName = $this->actionName;
        // 获取返回值
        $this->app->responseData = $controller->$actionName();
    }
}