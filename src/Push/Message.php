<?php


namespace Rongun\Push;

class Message
{
    //单播
    const TYPE_UNI_CAST = "unicast";

    //列播
    const TYPE_LIST_CAST = "listcast";

    //文件播
    const TYPE_FILE_CAST = "filecast";

    //广播
    const TYPE_BROAD_CAST = "broadcast";

    //组播
    const TYPE_GROUP_CAST = "groupcast";

    //通过alias进行推送
    const TYPE_CUSTOMIZE_CAST = "customizedcast";

    /**
     * @var string 消息发送类型
     */
    private $type = '';

    private $device_tokens;

    private $alias_type;

    private $alias;

    private $file_id;

    private $filter = [];

    public function __construct($type, $data)
    {
        $this->init($type, $data);
    }

    /**
     * @param $type
     * @param $data
     * @throws \Exception
     */
    private function init($type, $data)
    {
        switch ($type) {
            case self::TYPE_UNI_CAST:
                $this->device_tokens = (string)$data;
                break;
            case self::TYPE_LIST_CAST:
                if (!is_array($data)) {
                    $data = explode(",", $data);
                }
                if (count($data) > 500) {
                    throw new \Exception('device_tokens 不超过500个');
                }
                $this->device_tokens = implode(",", $data);
                break;
            case self::TYPE_BROAD_CAST:
                break;
            default:
                throw new \Exception("暂不支持");
        }
    }

    public function getData()
    {
        return [
            'type' => $this->type,
            "device_tokens" => $this->device_tokens,
            "alias_type" => $this->alias_type,
            "alias" => $this->alias,
            "file_id" => $this->file_id,
            "filter" => json_encode($this->filter)
        ];
    }
}
