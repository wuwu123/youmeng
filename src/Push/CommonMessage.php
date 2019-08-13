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


}