<?php
/**
 * Created by PhpStorm.
 * User: bluehead
 * Date: 2018/7/19
 * Time: 下午1:14
 */

namespace Rouis;


class TencentApiException extends \ErrorException
{
    public function isUserNotFoundException()
    {
        if ($this->code == "1301") { //user_not_found
            return true;
        }
        return false;
    }
}