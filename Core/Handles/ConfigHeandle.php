<?php
/**
 * Created by PhpStorm.
 * User: tony
 * Date: 2019-04-18
 * Time: 21:25
 */

namespace Core\Handles;

use Core\Core;
use Core\Handles\Handle;
use Core\Exception\CoreException;

class ConfigHeandle implements Handle
{
    private $app;

    private $config = [];

    public function __construct()
    {

    }

    public function __get($name = '')
    {
        return $this->$name;
    }

    public function __set($name, $value)
    {
       $this->$name = $value;
    }

    public function register(Core $app)
    {
        $this->app = $app;
        $app::$container->setSingle('config', $this);
        $this->loadConfig($app);
    }


    public function loadConfig(Core $app)
    {
        $this->config = require_once ($app->rootPath . '/Config/app.php');
    }

}
































