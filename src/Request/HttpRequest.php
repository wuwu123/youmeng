<?php


namespace Youmeng\Request;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;
use Youmeng\Config\Config;
use Youmeng\Tool;

class HttpRequest
{
    private $requestModel;

    public $retry = 0;

    private $errorMessage = null;

    /**
     * @var ResponseInterface
     */
    private $response = null;

    /**
     * @var Config
     */
    protected $config;

    /**
     * 是否成功
     * @var bool
     */
    private $isSuccess = false;

    /**
     * 请求结果
     * @var array
     */
    private $returnData = [];

    const SUCCESS = 'success';

    public function __construct(Config $config)
    {
        $this->isSuccess = true;
        $this->returnData = [];
        $this->requestModel = new Client(
            [
                'timeout' => $config->getTimeOut()
            ]
        );
        $this->retry = $config->getRetryNum();
    }

    public function post($path, $data = [])
    {
        $this->request('POST', $path, $data);
        return $this;
    }

    public function get($path, $data = [])
    {
        $this->request('GET', $path, $data);
        return $this;
    }

    /**
     * 是否请求成功
     * @return bool
     */
    public function isOk(): bool
    {
        return $this->isSuccess;
    }

    /**
     * 请求结果
     * @return array|null
     */
    public function getData()
    {
        if ($this->isOk()) {
            return $this->returnData;
        }
        return $this->errorMessage;
    }

    /**
     * 获取请求url
     * @param $method
     * @param $path
     * @param array $data
     * @return string
     */
    public function getUrl($method, $path, $data = [])
    {
        $url = $path ? trim($this->config->getBaseUrl(), "/") . '/' . ltrim($path, "/") : $this->config->getBaseUrl();
        $postBody = json_encode($data);
        $sign = md5($method . $url . $postBody . $this->config->getMasterSecret());
        return $url . "?sign=" . $sign;
    }

    /**
     * 请求实体
     * @param $method
     * @param $path
     * @param array $data
     * @return $this
     */
    private function request($method, $path, $data = [])
    {
        $this->isSuccess = false;
        try {
            $data['appkey'] = $this->config->getAppKey();
            $data['timestamp'] = time();
            $url = $this->getUrl($method, $path, $data);
            /*** @var ResponseInterface $request */
            $this->response = Tool::retry($this->retry, function () use ($method, $url, $data) {
                $this->requestModel->request($method, $url, ['json' => $data]);
            });
            $data = \GuzzleHttp\json_decode($this->response->getBody(), true);
            $ret = $data['ret'] ?? '';
            if ($ret == self::SUCCESS) {
                $this->isSuccess = true;
                $this->returnData = $data;
                return $this;
            }
            $this->errorMessage = $data['error_msg'] ?? $data['error_code'] ?? '请求失败';
        } catch (\Throwable $e) {
            $this->errorMessage = $e->getMessage();
        }
        return $this;
    }

    /**
     * @return ResponseInterface
     */
    public function getResponse(): ?ResponseInterface
    {
        return $this->response;
    }
}
