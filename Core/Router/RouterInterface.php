<?php
/**
 * Created by PhpStorm.
 * User: tony
 * Date: 2019-04-18
 * Time: 20:56
 */

namespace Core\Router;

use Core\Router\Router;

/**
 * 路由策略接口
 * Interface RouteInterface
 * @package Core\Router
 */
interface RouterInterface
{
    /**
     * 路由方法
     * @param \Core\Route\Router $entrance
     * @return mixed
     */
    public function route(Router $entrance);
}