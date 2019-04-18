<?php
/**
 * Created by PhpStorm.
 * User: tony
 * Date: 2019-04-18
 * Time: 20:59
 */

namespace Core\Route;

use Core\Router\RouterInterface;
use Core\Router\Router;

class PathInfo implements RouterInterface
{

    public function route(Router $entrance)
    {
        if (strpos($entrance->requestUri, '?')) {
            preg_match_all('/^\/(.*)\?/', $entrance->requestUri, $uri);
        } else {
            preg_match_all('/^\/(.*)/', $entrance->requstUri, $uri);
        }

        $uri = explode('/', $uri);
        switch (count($uri)) {
            case 1:
                $entrance->actionName = $uri[0];
                break;
            case 2:
                $entrance->controllerName = $uri[0];
                $entrance->actionName = $uri[1];
                break;
            case 3:
                $entrance->moduleName = $uri[0];
                $entrance->controllerName = $uri[1];
                $entrance->actionName = $uri[2];
                break;
            default:
                break;
        }

    }
}