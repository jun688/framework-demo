<?php
/**
 * Created by PhpStorm.
 * User: tony
 * Date: 2019-04-18
 * Time: 17:06
 */

namespace Core;

use Core\Core;

class Response
{
    private $app = null;

    public function __construct(Core $app)
    {
        $this->app = $app;
    }

    /**
     * http响应
     * @param $response
     */
    public function response($response)
    {
        header('Content-Type:Application/json; Charset=utf-8');
        die(json_encode($response, JSON_UNESCAPED_UNICODE));
    }

    /**
     * REST风格 成功响应
     *
     * @param  mixed $response 响应内容
     * @return json
     */
    public function restSuccess($response, $code = 200, $msg = '')
    {
        header('Content-Type:Application/json; Charset=utf-8');
        die(json_encode([
            'code'    => $code,
            'message' => $msg,
            'result'  => $response
        ],JSON_UNESCAPED_UNICODE)
        );
    }

}