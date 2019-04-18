<?php
/**
 * Created by PhpStorm.
 * User: tony
 * Date: 2019-04-18
 * Time: 21:23
 */

namespace Core\Handles;

use Core\Core;
use Core\Exception\CoreException;
use ReflectionClass;
use Closure;
use Core\Router\Routers;

class RouterHandle implements Handle
{
    public function register(Core $app)
    {
        (new Routers())->init($app);
    }
}