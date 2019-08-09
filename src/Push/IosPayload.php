<?php


namespace Rongun\Push;

class IosPayload implements PayLoad
{
    private $otherParams = [];

    private $content_available = false;

    private $alert = [];

    /**
     * @param array $otherParams
     * @return $this
     */
    public function setOtherParams(array $otherParams)
    {
        $this->otherParams = $otherParams;
        return $this;
    }

    /**
     * @param bool $content_available
     * @return $this
     */
    public function setContentAvailable(bool $content_available)
    {
        $this->content_available = $content_available;
        return $this;
    }

    /**
     * @param string $title
     * @param string $subtitle
     * @param string $body
     * @return $this
     */
    public function setAlert(string $title, string $subtitle, string $body = "")
    {
        $this->alert = [
            'title' => $title,
            'subtitle' => $subtitle,
            'body' => $body
        ];
        return $this;
    }


    private function __construct()
    {
    }

    public static function make()
    {
        return new self();
    }

    private function checkOtherParams()
    {
        if ($this->otherParams) {
            $count = count($this->otherParams);
            $checkData = ['d' => 'd', 'p' => 'p'];
            if (count(array_map($this->otherParams, $checkData)) != ($count + count($checkData))) {
                throw new \Exception(implode(',', array_keys($checkData)) . "为友盟保留字段");
            }
        }
    }

    /**
     * 获取请求体
     * @return array
     * @throws \Exception
     */
    public function getData(): array
    {
        $this->checkOtherParams();
        $available = (int)$this->content_available;
        if ($available != 1 && empty($this->alert)) {
            throw new \Exception("alert 内容不能为空");
        }
        $data = $this->otherParams;
        $aps['content-available'] = $available;
        if ($this->alert) {
            $aps['alert'] = $this->alert;
        }
        $data['aps'] = $aps;
        return $data;
    }
}
