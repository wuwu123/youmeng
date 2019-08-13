<?php


namespace Youmeng\Safety;


use Youmeng\Config\Config;

/**
 * 安全限制
 * Class Safety
 * @package Youmeng\Safety
 */
class Safety
{
    /**
     * @var Config
     */
    private $config;

    private $cacheKey;

    const PRE = 'youmeng';

    public function __construct(Config $config)
    {
        $this->cacheKey = null;
        $this->config = $config;
    }

    public function getRule($type): array
    {
        $rules = [];
        $safety = $this->config->getSafety();
        foreach ($safety as $row) {
            if (isset($row['type'], $row['time'], $row['count']) && $row['time'] && $row['count']) {
                if ($row['type'] == 'all' || $type == $row['type']) {
                    $row['message'] = sprintf('%s 类型的消息 ， %s 时间内只能触发 %s 次', $row['type'], $rules['time'], $row['count']);
                    $rules[] = $row;
                }
            }
        }
        return $rules;
    }

    public function checkKey(string $url, array $data = []): array
    {
        $safety = $this->config->getSafety();
        if (empty($safety)) {
            return [true, ''];
        }
        unset($data['timestamp']);
        $type = $data['type'] ?? null;
        if (!$type) {
            return [true, ''];
        }
        $rules = $this->getRule($type);
        $this->cacheKey = self::PRE . ":" . $type . ":" . $url . ":" . md5(serialize($data));
        foreach ($rules as $ruleRow) {
            $key = $this->cacheKey . ":" . $ruleRow['time'] . ":" . $ruleRow['count'];
            if ($this->config->getRedisModel()->incrBy($key, 1) > $ruleRow['count']) {
                return [false, $ruleRow['message']];
            }
        }
        return [true, ''];
    }

}