<?php


namespace Youmeng\Push;

class CommonMessage
{
    private $messageType;
    private $messageData;

    private $title = '';
    private $desc = '';
    private $ticker = '';

    private $url = '';

    private $otherParams = [];


    private $alias_type = '';

    private $iosBadge = '';

    /**
     * @return string
     */
    public function getIosBadge()
    {
        return $this->iosBadge;
    }

    /**
     * @param string $iosBadge
     * @return $this
     */
    public function setIosBadge($iosBadge)
    {
        $this->iosBadge = $iosBadge;
        return $this;
    }

    /**
     * 定制的
     */
    private $androidCustom;

    /**
     * @return mixed
     */
    public function getAndroidCustom()
    {
        return $this->androidCustom;
    }

    /**
     * @param mixed $androidCustom
     * @return $this
     */
    public function setAndroidCustom($androidCustom)
    {
        $this->androidCustom = $androidCustom;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMessageType()
    {
        return $this->messageType;
    }

    /**
     * @param mixed $messageType
     * @return $this
     */
    public function setMessageType($messageType)
    {
        $this->messageType = $messageType;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMessageData()
    {
        return $this->messageData;
    }

    /**
     * @param mixed $messageData
     * @return $this
     */
    public function setMessageData($messageData)
    {
        $this->messageData = $messageData;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDesc()
    {
        return $this->desc;
    }

    /**
     * @param mixed $desc
     * @return $this
     */
    public function setDesc($desc)
    {
        $this->desc = $desc;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTicker()
    {
        return $this->ticker;
    }

    /**
     * @param mixed $ticker
     * @return $this
     */
    public function setTicker($ticker)
    {
        $this->ticker = $ticker;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return array
     */
    public function getOtherParams(): array
    {
        return $this->otherParams;
    }

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
     * @return string
     */
    public function getAliasType(): string
    {
        return $this->alias_type;
    }

    /**
     * @param string $alias_type
     * @return $this
     */
    public function setAliasType(string $alias_type)
    {
        $this->alias_type = $alias_type;
        return $this;
    }
}
