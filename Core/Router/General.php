<?php
/**
 * Created by PhpStorm.
 * User: tony
 * Date: 2019-04-18
 * Time: 20:58
 */

namespace Core\Route;

use Core\Router\Router;
use Core\Router\RouterInterface;

class General implements RouterInterface
{
    /**
     * 路由方法
     * @param Router $entrance
     * @return mixed|void
     */
    public function route(Router $entrance)
    {
        $app = $entrance->app;
        $request = $app::$container->get('request');
        $moduleName = $request->request('module');
        $controllerName = $request->request('controller');
        $actionName = $request->request('action');

        if (!empty($moduleName)) {
            $entrance->moduleName = $moduleName;
        }

        if (!empty($controllerName)) {
            $entrance->actionName = $actionName;
        }
    }
}