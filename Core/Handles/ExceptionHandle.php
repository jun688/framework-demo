<?php
/**
 * Created by PhpStorm.
 * User: tony
 * Date: 2019-04-18
 * Time: 21:23
 */

namespace Core\Handles;
use Core\Core;
use Core\Handles\Handle;
use Core\Exception\CoreException;


class ExceptionHandle implements Handle
{

    /**
     * 错误信息
     *
     * @var array
     */
    private $info = [];

    /**
     * 注册未捕获异常函数
     *
     * @param $app 框架实例
     * @return void
     */
    public function register(Core $app)
    {
        set_exception_handler([$this, 'exceptionHandler']);
    }
    /**
     * 未捕获异常函数
     *
     * @param  object $exception 异常
     * @return void
     */
    public function exceptionHandler($exception)
    {
        $this->info = [
            'code'       => $exception->getCode(),
            'message'    => $exception->getMessage(),
            'file'       => $exception->getFile(),
            'line'       => $exception->getLine(),
            'trace'      => $exception->getTrace(),
            'previous'   => $exception->getPrevious()
        ];
        $this->end();
    }
    /**
     * 脚本结束
     * @return　mixed
     */
    private function end()
    {
        CoreException::reponseErr($this->info);
    }
}
