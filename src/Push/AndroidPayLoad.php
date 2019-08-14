<?php


namespace Youmeng\Push;

class AndroidPayLoad implements PayLoad
{
    const TYPE_NOTIFICATION = 'notification';

    const TYPE_MESSAGE = 'message';

    const OPEN_APP = 'go_app';
    const OPEN_URL = 'go_url';
    const OPEN_ACTIVITY = 'go_activity';
    const OPEN_CUSTOM = 'go_custom';
    /**
     * @var string notification(通知)、message(消息)
     */
    private $display_type = 'notification';

    /**
     * @var string 通知栏提示文字
     */
    private $ticker = null;

    /**
     * @var string 通知标题
     */
    private $title = null;

    /**
     * @var string 通知文字描述
     */
    private $text = null;

    /**
     * @var string 图标没有的默认应用
     */
    private $icon;

    /**
     * @var int 自定义通知样式
     */
    private $builder_id = 0;

    /**
     * @var string 是否震动
     */
    private $play_vibrate = true;

    /**
     * @var string 是否闪灯
     */
    private $play_lights = false;

    /**
     * @var string 是否发出声音
     */
    private $play_sound = true;

    /**
     * @var string 点击"通知"的后续行为 默认为"go_app"，值可以为:
     *   "go_app": 打开应用
     *   "go_url": 跳转到URL
     *   "go_activity": 打开特定的activity
     *   "go_custom": 用户自定义内容。
     */
    private $after_open = null;

    /**
     * @var string|array 打开其他参数
     */
    private $after_open_params;

    /**
     * @var array 扩展字段
     */
    private $extra = [];

    private $custom;

    /**
     * @return mixed
     */
    public function getCustom()
    {
        return $this->custom;
    }

    /**
     * @param mixed $custom
     * @return $this
     */
    public function setCustom($custom)
    {
        $this->custom = $custom;
        return $this;
    }

    /**
     * @param string $display_type
     * @return $this
     */
    public function setDisplayType(string $display_type)
    {
        $this->display_type = $display_type;
        return $this;
    }

    /**
     * @param string $ticker
     * @return $this
     */
    public function setTicker(string $ticker)
    {
        $this->ticker = $ticker;
        return $this;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @param string $text
     * @return $this
     */
    public function setText(string $text)
    {
        $this->text = $text;
        return $this;
    }

    /**
     * @param string $icon
     * @return $this
     */
    public function setIcon(string $icon)
    {
        $this->icon = $icon;
        return $this;
    }

    /**
     * @param int $builder_id
     * @return $this
     */
    public function setBuilderId(int $builder_id)
    {
        $this->builder_id = $builder_id;
        return $this;
    }

    /**
     * @param string $play_vibrate
     * @return $this
     */
    public function setPlayVibrate(string $play_vibrate)
    {
        $this->play_vibrate = $play_vibrate;
        return $this;
    }

    /**
     * @param string $play_lights
     * @return $this
     */
    public function setPlayLights(string $play_lights)
    {
        $this->play_lights = $play_lights;
        return $this;
    }

    /**
     * @param string $play_sound
     * @return $this
     */
    public function setPlaySound(string $play_sound)
    {
        $this->play_sound = $play_sound;
        return $this;
    }

    /**
     * @param string $after_open
     * @return $this
     */
    public function setAfterOpen(string $after_open)
    {
        $this->after_open = $after_open;
        return $this;
    }

    /**
     * @param array|string $after_open_params
     * @return $this
     */
    public function setAfterOpenParams($after_open_params)
    {
        $this->after_open_params = $after_open_params;
        return $this;
    }

    /**
     * @param array $extra
     * @return $this
     */
    public function setExtra(array $extra)
    {
        $this->extra = $extra;
        return $this;
    }

    private function __construct()
    {
    }

    public static function make()
    {
        return new self();
    }

    public function getValidTitle()
    {
        return $this->title ?? $this->text ?? $this->ticker;
    }

    /**
     * 获取数据
     * @return array
     * @throws \Exception
     */
    public function getData(): array
    {
        $data['display_type'] = $this->display_type;
        $validTitle = $this->getValidTitle();
        $body = [
            'title' => $this->title ?? $validTitle,
            'ticker' => $this->ticker ?? $validTitle,
            'text' => $this->text ?? $validTitle,
            'play_vibrate' => $this->play_vibrate,
            'play_lights' => $this->play_lights,
            'play_sound' => $this->play_sound,
        ];
        if ($this->icon) {
            $body['icon'] = $this->icon;
        }

        if ($this->builder_id) {
            $body['builder_id'] = $this->builder_id;
        }

        if ($this->after_open) {
            $body['after_open'] = $this->after_open;
            switch ($this->after_open) {
                case self::OPEN_APP:
                    break;
                case self::OPEN_URL:
                    if (empty($this->after_open_params) || !is_string($this->after_open_params) || 0 !== strpos($this->after_open_params, "http")) {
                        throw new \Exception("通知栏点击后跳转的URL，要求以http或者https开头");
                    }
                    $body['url'] = $this->after_open_params;
                    break;
                case self::OPEN_ACTIVITY:
                    if (empty($this->after_open_params) || (!is_string($this->after_open_params) && !is_array($this->after_open_params))) {
                        throw new \Exception("after_open_params 必须为字符串");
                    }
                    $body['activity'] = is_array($this->after_open_params) ? json_encode($this->after_open_params) : $this->after_open_params;
                    break;
                case self::OPEN_CUSTOM:
                    if (!is_array($this->after_open_params)) {
                        throw new \Exception("after_open_params 必须为数组");
                    }
                    $body['custom'] = json_encode($this->after_open_params);
                    break;
                default:
                    throw new \Exception("after_open 无效的类型" . $this->after_open);

            }
        }
        if (!isset($body['custom'])) {
            $body['custom'] = $this->custom;
        }
        if (
            $this->display_type == self::TYPE_MESSAGE ||
            ($this->display_type == self::TYPE_NOTIFICATION && $this->after_open == self::OPEN_CUSTOM)
        ) {
            if (empty($body['custom'])) {
                throw new \Exception("custom 不能为空");
            }
        }
        $data['body'] = $body;
        if ($this->extra) {
            $data['extra'] = $this->extra;
        }
        return $data;
    }
}
