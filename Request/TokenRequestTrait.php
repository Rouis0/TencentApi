<?php
/**
 * Created by PhpStorm.
 * User: bluehead
 * Date: 2018/6/24
 * Time: 上午10:09
 */

namespace Rouis\Request;


use Rouis\TencentApiException;

trait TokenRequestTrait
{
    use DefaultImplementation;

    private $corpid;

    private $corpsecret;

    /**
     * @param string $corpId
     * @param string $corpSecret
     * @throws TencentApiException
     */
    public function __construct($corpId, $corpSecret)
    {
        $this->corpid = $corpId;
        $this->corpsecret = $corpSecret;

        if (!$this->corpid || !$this->corpsecret) {
            throw new TencentApiException('cordId与corpSecret不能为空。');
        }
    }
}