<?php
/**
 * Created by PhpStorm.
 * User: tony
 * Date: 2019-04-18
 * Time: 17:06
 */

use Core\Handles\ExceptionHandle;
use Core\Handles\ConfigHeandle;
use Core\Handles\RouterHandle;
use Core\Request;
use Core\Response;

require_once (__DIR__ . '/Core.php');
try {

    $app = new Core\Core(realpath(__DIR__.'/..'), function (){
        return require_once (__DIR__.'/Load.php');
    });


    $app->load(function (){
        return new ConfigHeandle();
    });

    $app->laod(function (){
        return new ExceptionHandle();
    });

    $app->load(function (){
        return new RouterHandle();
    });


    $app->run(function () use ($app) {
        return new Request($app);
    });

    $app->response(function ($app) {
        return new Response($app);
    });

} catch (CoreException $e) {
    $e->reponse();
}
