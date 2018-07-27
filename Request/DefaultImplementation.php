<?php
/**
 * Created by PhpStorm.
 * User: bluehead
 * Date: 2018/7/19
 * Time: 下午1:50
 */

namespace Rouis\Request;


trait DefaultImplementation
{
    public function getRequestRoot()
    {
        return self::root;
    }

    public function getRequestMethod()
    {
        return static::method;
    }

    public function getRequestUri()
    {
        return self::root . static::path;
    }

    /**
     * 【默认方法】返回一个请求时的请求包结构体
     * @return array
     */
    public function getRequestFields()
    {
        $body = [];
        foreach ($this as $key => $value) {
            if (!is_null($value)) {
                $body[$key] = $value;
            }
        }
        return $body;
    }

    /**
     * @param mixed $request
     */
    public function setRequest($request)
    {
        if (is_array($request)) {
            $requestData = $request;
        } else {
            $requestData = get_object_vars($request);
        }
        foreach ($requestData as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    public function isUploadFile()
    {
        return false;
    }
}