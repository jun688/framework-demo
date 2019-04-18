<?php
/**
 * Created by PhpStorm.
 * User: tony
 * Date: 2019-04-18
 * Time: 17:06
 */

namespace Core;

use Core\Core;
use Core\Exception\CoreException;

class  Request
{
    /**
     * 请求hander参数
     * @var array
     */
    private $headerparams = [];


    /**
     * 请求server参数
     * @var array
     */
    private $serverParams = [];

    /**
     * 请求参数
     * @var array
     */
    private $requestParams = [];

    /**
     * 请求get参数
     * @var array
     */
    private $getParams = [];


    /**
     * 请求post参数
     * @var array
     */
    private $postParams = [];

    /**
     * cookie
     * @var array
     */
    private $cookie = [];

    /**
     * file
     * @var array
     */
    private $file = [];

    /**
     * 请求方法
     * @var string
     */
    private $method = '';

    /**
     * 服务器IP
     * @var string
     */
    private $serverIp = '';

    /**
     * 客户端IP
     * @var string
     */
    private $clientIp = '';

    /**
     * 请求开始时间
     * @var int
     */
    private $beginTime = 0;

    /**
     * 请求结束时间
     * @var int
     */
    private $endTime = 0;

    /**
     * 请求耗时
     * @var int
     */
    private $consumeTime = 0;

    /**
     * 请求身份标识
     * @var string
     */
    private $requestId = '';

    /**
     * 请求参数
     * Request constructor.
     * @param \Core\Core $app
     */
    public function __construct(Core $app)
    {
        $this->serverParams = $_SERVER;
        $this->method = isset($_SERVER['REQUEST_METHOD']) ? strtolower($_SERVER['REQUEST_METHOD']) : 'get';
        $this->serverIp = isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : '';
        $this->clientIp = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
        $this->beginTime = isset($_SERVER['REQUEST_TIME_FLOAT']) ? _SERVER['REQUEST_TIME_FLOAT'] : microtime(true);
        $this->requestParams = $_REQUEST;
        $this->getParams = $_GET;
        $this->postParams = $_POST;
    }

    /**
     * get参数
     * @param string $value 参数名
     * @param string $default 默认值
     * @param bool $checkEmpty 为空是否返回默认值
     * @return string
     */
   public function get($value = '', $default = '', $checkEmpty = true)
   {
       if (!isset($this->getParams[$value])) {
           return '';
       }

       if (empty($this->getParams[$value]) && $checkEmpty) {
           return $default;
       }

       return htmlspecialchars($this->getParams[$value]);
   }

    /**
     * post参数
     * @param string $value
     * @param string $default
     * @param bool $checkEmpty
     * @return string
     */
   public function post($value = '', $default = '', $checkEmpty = true)
   {
       if (!isset($this->postParams[$value])) {
           return '';
       }

       if (empty($this->postParams[$value]) && $checkEmpty) {
           return $default;
       }

       return htmlspecialchars($this->postParams[$value]);
   }

    /**
     * request请求
     * @param string $value
     * @param string $default
     * @param bool $checkEmpty
     * @return string
     */
   public function request($value = '', $default = '', $checkEmpty = true)
   {
       if (!isset($this->requestParams[$value])) {
           return '';
       }

       if (empty($this->requestParams[$value]) && $checkEmpty) {
           return $default;
       }

       return htmlspecialchars($this->requestParams[$value]);
   }

    /**
     * 获取所有参数
     * @return array
     */
   public function all()
   {
       $res = array_merge($this->postParams, $this->getParams);
       foreach ($res as &$v) {
           $v = htmlspecialchars($v);
       }

       return $res;
   }

    /**
     * server参数
     * @param string $value
     * @return mixed|string
     */
   public function server($value = '')
   {
       if (isset($this->serverParams[$value])) {
           return $this->serverParams[$value];
       }

       return '';
   }

    /**
     * 参数验证
     * @param string $paramName
     * @param string $rule
     * @param int $length
     * @throws CoreException
     */
   public function check($paramName = '', $rule = '', $length = 0)
   {
       if (!is_int($length)) {
           throw new CoreException(400, 'length type is not int');
       }

       if ($rule === 'require') {
           if (!empty($this->request($paramName))) {
               return;
           }
           throw new CoreException(404, "param {$paramName}");
       }

       if ($rule === 'length') {
           if (strlen($this->request($paramName)) == $length) {
               return;
           }

           throw new CoreException(400, "param {$paramName} length is not {$length}");
       }

       if ($rule == 'number') {
           if (is_numeric($this->request($paramName))) {
               return;
           }

           throw new CoreException(400, "{$paramName} type is not number");
       }
   }
}