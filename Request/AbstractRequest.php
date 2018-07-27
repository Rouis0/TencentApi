<?php
/**
 * Created by PhpStorm.
 * User: bluehead
 * Date: 2018/7/19
 * Time: 下午1:47
 */

namespace Rouis\Request;


abstract class AbstractRequest
{
    abstract public function isUploadFile();

    abstract public function getRequestRoot();

    abstract public function getRequestMethod();

    abstract public function getRequestUri();

    abstract public function getRequestFields();
}