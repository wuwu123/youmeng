<?php


namespace Rongun\Config;

class Config
{
    /**
     * 应用唯一标识。
     * @var string
     */
    private $appKey = '';

    /**
     * 服务器秘钥
     * @var string
     */
    private $masterSecret = '';

    /**
     * base url
     * @var string
     */
    private $baseUrl = 'https://msgapi.umeng.com';

    /**
     * 失败重试次数
     * @var int
     */
    private $retryNum = 0;

    /**
     * 超时时间
     * @var float
     */
    private $timeOut = 5.0;

    /**
     * @return string
     */
    public function getAppKey(): string
    {
        return $this->appKey;
    }

    /**
     * @return string
     */
    public function getMasterSecret(): string
    {
        return $this->masterSecret;
    }

    /**
     * @return string
     */
    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    /**
     * @return int
     */
    public function getRetryNum(): int
    {
        return $this->retryNum;
    }

    /**
     * @return float
     */
    public function getTimeOut(): float
    {
        return $this->timeOut;
    }


    public function __construct(array $data)
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }
}
