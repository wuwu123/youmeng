<?php


namespace Youmeng\Push;

use Youmeng\Tool;

/**
 * 发送策略
 * Class Policy
 * @package Youmeng\Push
 */
class Policy
{
    /**
     * 定时发送
     * @var string
     */
    private $start_time = '';

    /**
     * 有效期时间
     * @var string
     */
    private $expire_time = '';

    /**
     * 发送限速，每秒发送的最大条数。最小值1000
     * @var int
     */
    private $max_send_num = 0;

    /**
     * 开发者对消息的唯一标识，服务器会根据这个标识避免重复发送
     * @var string
     */
    private $out_biz_no = '';

    /**
     * 多条带有相同apns_collapse_id的消息，iOS设备仅展示 最新的一条，字段长度不得超过64bytes
     * @var string
     */
    private $apns_collapse_id = '';


    /**
     * @param string $start_time
     * @return $this
     */
    public function setStartTime(string $start_time)
    {
        $this->start_time = $start_time;
        return $this;
    }

    /**
     * @param string $expire_time
     * @return $this
     */
    public function setExpireTime(string $expire_time)
    {
        $this->expire_time = $expire_time;
        return $this;
    }

    /**
     * @param int $max_send_num
     * @return $this
     */
    public function setMaxSendNum(int $max_send_num)
    {
        $this->max_send_num = $max_send_num;
        return $this;
    }

    /**
     * @param string $out_biz_no
     * @return $this
     */
    public function setOutBizNo(string $out_biz_no)
    {
        $this->out_biz_no = $out_biz_no;
        return $this;
    }

    /**
     * @param string $apns_collapse_id
     * @return $this
     */
    public function setApnsCollapseId(string $apns_collapse_id)
    {
        $this->apns_collapse_id = $apns_collapse_id;
        return $this;
    }

    public static function make()
    {
        return new self();
    }

    public function getData()
    {
        $data = [];
        if ($this->start_time && Tool::isTime($this->start_time)) {
            $data['start_time'] = $this->start_time;
        }

        if ($this->expire_time && Tool::isTime($this->expire_time)) {
            $data['expire_time'] = $this->expire_time;
        }

        $this->out_biz_no && $data['out_biz_no'] = $this->out_biz_no;
        $this->apns_collapse_id && $data['apns_collapse_id'] = $this->apns_collapse_id;
        $this->max_send_num && $data['max_send_num'] = (int)$this->max_send_num;
        return $data;
    }
}
