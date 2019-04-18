<?php
/**
 * Created by PhpStorm.
 * User: tony
 * Date: 2019-04-18
 * Time: 15:00
 */

namespace Core\Exception;

use Exception;
use Core\Log;

class CoreException extends Exception
{
    private static $handleException = false;

    /**
     * 异常code
     * @var array
     */
    private $httpCode = [
        400 => 'Bad Request',
        403 => 'Forbidden',
        404 => 'Not Found',
        500 => 'Internet Server Error',
        503 => 'Service Unavailable'
    ];

    /**
     * 错误信息补充
     * CoreException constructor.
     * @param int $code
     * @param string $extra
     */
    public function __construct(int $code = 0, $extra = '')
    {
        $this->code = $code;
        if (empty($extra)) {
            $this->message = $this->httpCode[$code];
            return;
        }

        $this->message = $extra . ' ' . $this->httpCode[$code];
    }

    /**
     * 响应
     */
    public function reponse()
    {
        $data = [
          '__codeError' => [
              'code' => $this->getCode(),
              'message' => $this->getMessage(),
              'infomations' => [
                  'file'  => $this->getFile(),
                  'line'  => $this->getLine(),
                  'trace' => $this->getTrace()
              ]
          ]
        ];

        Log::error('repose: ', $data);

        register_shutdown_function(function () use ($data) {
            header('Content-Type:Application/json; Charset=utf-8');
            die(json_encode($data, JSON_UNESCAPED_UNICODE));
        });
    }

    /**
     * 异常响应
     * @param $e
     */
    public static function reponseErr($e)
    {
        $data = [
            '__coreError' => [
                'code'    => 500,
                'message' => $e,
                'infomations'  => [
                    'file'  => $e['file'],
                    'line'  => $e['line'],
                ]
            ]
        ];

        if (self::$handleException) {
            Log::error('reponseErr:', $data);
            return;
        }

        self::$handleException = true;

        Log::error('responseErr:', $data);

        header('Content-Type:Application/json; Charset=utf-8');
        die(json_encode($data, JSON_UNESCAPED_UNICODE));
    }

}