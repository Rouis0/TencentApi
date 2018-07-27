<?php
/**
 * Created by PhpStorm.
 * User: bluehead
 * Date: 2018/7/19
 * Time: 下午1:15
 */

namespace Rouis;


class TencentApiError
{
    public $code;

    public $message;

    /**
     * @param string $code
     * @param string $message
     * TencentApiError constructor.
     */
    public function __construct($code, $message)
    {
        $this->code = $code;
        $this->message = $message;
    }
}