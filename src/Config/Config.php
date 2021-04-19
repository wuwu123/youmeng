<?php


namespace Youmeng\Config;

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
    private $timeOut = 2.0;

    /**
     * 正式/测试模式。默认为true
     * @var string
     */
    private $production_mode = 'true';

    /**
     * redis Model
     * @var \Redis
     */
    private $redisModel = null;

    /**
     * 安全规则
     * @var array
     * [['type'=>'messageType' , time => 4 , 'count'=>1]]
     */
    private $safety = [];

    /**
     * @var null|callable
     */
    private $handler = null;

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

    /**
     * @return string
     */
    public function getProductionMode(): string
    {
        return $this->production_mode;
    }

    /**
     * @return \Redis
     */
    public function getRedisModel()
    {
        return $this->redisModel;
    }

    /**
     * @return array
     */
    public function getSafety(): array
    {
        return $this->safety;
    }

    /**
     * @param string $appKey
     * @return $this
     */
    public function setAppKey(string $appKey)
    {
        $this->appKey = $appKey;
        return $this;
    }

    /**
     * @param string $masterSecret
     * @return $this
     */
    public function setMasterSecret(string $masterSecret)
    {
        $this->masterSecret = $masterSecret;
        return $this;
    }

    /**
     * @param string $baseUrl
     * @return $this
     */
    public function setBaseUrl(string $baseUrl)
    {
        $this->baseUrl = $baseUrl;
        return $this;
    }

    /**
     * @param int $retryNum
     * @return $this
     */
    public function setRetryNum(int $retryNum)
    {
        $this->retryNum = $retryNum;
        return $this;
    }

    /**
     * @param float $timeOut
     * @return $this
     */
    public function setTimeOut(float $timeOut)
    {
        $this->timeOut = $timeOut;
        return $this;
    }

    /**
     * @param string $production_mode
     * @return $this
     */
    public function setProductionMode(string $production_mode)
    {
        $this->production_mode = $production_mode;
        return $this;
    }

    /**
     * @param \Redis $redisModel
     * @return $this
     */
    public function setRedisModel($redisModel)
    {
        $this->redisModel = $redisModel;
        return $this;
    }

    /**
     * @param array $safety
     * @return $this
     */
    public function setSafety(array $safety)
    {
        $this->safety = $safety;
        return $this;
    }

    public function getHandler()
    {
        return $this->handler;
    }

    public function setHandler(?callable $handler)
    {
        $this->handler = $handler;
        return $this;
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
