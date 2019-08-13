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
    public function getRedisModel(): \Redis
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


    public function __construct(array $data)
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }
}
