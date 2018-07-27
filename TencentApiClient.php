<?php
/**
 * Created by PhpStorm.
 * User: bluehead
 * Date: 2018/7/19
 * Time: 下午1:16
 */

namespace Rouis;


use Rouis\Request\AbstractRequest;
use GuzzleHttp;

abstract class TencentApiClient
{
    protected $token;

    protected $corpId;

    protected $corpSecret;

    public function __construct($corpId, $corpSecret)
    {
        $this->corpId = $corpId;
        $this->corpSecret = $corpSecret;
    }

    /**
     * @param AbstractRequest $request
     * @param boolean $needToken
     * @param string $selfToken
     * @return \stdClass
     * @throws TencentApiException
     */
    public function executeRequest(AbstractRequest $request, $needToken = true, $selfToken = null)
    {
        $url = $request->getRequestUri();
        $args = $request->getRequestFields();

        if ($needToken) {
            $token = $selfToken ?: $this->getToken();
            $url .= '?access_token=' . $token;
        }
        if ($request->getRequestMethod() == 'post') {   //Post Method
            $response = $this->HttpPostParseToJson($url, $args, $request->isUploadFile());
        } else {    //Get Method
            $url .= (strpos($url, '?') !== false ? '&' : '?') . http_build_query($args);
            $response = $this->HttpGetUrl($url);
        }

        $responseJson = json_decode($response);
        if ($error = $this->getErrorFromResponse($responseJson)) {
            throw new TencentApiException($error->message, $error->code);
        } else {
            return $responseJson;
        }
    }

    /**
     * @param string $url
     * @param array $args
     * @param bool $isUploadFile
     * @throws TencentApiException
     * @return string
     */
    private function HttpPostParseToJson($url, $args, $isUploadFile = false)
    {
        $realUrl = $url;
        $client = new GuzzleHttp\Client();
        if (!$isUploadFile) {
            $response = $client->post($url, [
                'headers' => [
                    'Content-Type' => 'application/json; charset=utf-8'
                ],
                'body' => GuzzleHttp\json_encode($args, JSON_UNESCAPED_UNICODE)
            ]);
        } else {
            $realUrl .= (strpos($url, '?') !== false ? '&' : '?') . http_build_query(['type' => $args['type']]);
            $contents = isset($args['filePath']) ? fopen($args['filePath'], 'r') : \GuzzleHttp\Psr7\stream_for($args['fileStream']);
            try {
                $response = $client->request('POST', $realUrl, [
                    'multipart' => [
                        [
                            'name' => 'media',
                            'contents' => $contents,
                            'filename' => $args['postName'],
                            'headers' => [
                                'Content-Type' => 'application/octet-stream',
                            ],
                        ]
                    ]
                ]);
            } catch (GuzzleHttp\Exception\GuzzleException $exception) {
                throw new TencentApiException($exception->getCode(), $exception->getMessage());
            }

        }
        return $response->getBody()->getContents();
    }

    /**
     * @param string $url
     * @return string
     */
    private function HttpGetUrl($url)
    {
        $client = new GuzzleHttp\Client();
        $response = $client->get($url);
        return $response->getBody()->getContents();
    }


    /**
     * @return string $access_token
     * @throws TencentApiException
     */
    private function getToken()
    {
        if (is_null($this->token)) {
            $this->setToken();
        }
        return $this->token;
    }

    /**
     * @throws TencentApiException
     */
    abstract protected function setToken();

    private function getErrorFromResponse($response)
    {
        if (isset($response->errcode)) {
            return new TencentApiError(400, '数据异常');
        }

        if ($response->errcode) {
            return new TencentApiError($response->errcode, $response->errmsg);
        }
        return true;
    }

}