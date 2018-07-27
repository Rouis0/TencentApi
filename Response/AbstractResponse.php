<?php
/**
 * Created by PhpStorm.
 * User: bluehead
 * Date: 2018/7/19
 * Time: 下午2:02
 */

namespace Rouis\Response;


abstract class AbstractResponse
{
    private $errCode;

    private $errMsg;


    /**
     * @return string
     */
    public function getErrCode()
    {
        return $this->errCode;
    }

    /**
     * @return string
     */
    public function getErrMsg()
    {
        return $this->errMsg;
    }
}