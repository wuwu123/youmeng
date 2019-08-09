<?php


namespace Rongun\Request;

use Rongun\Config\Config;
use Rongun\Push\AndroidPayLoad;
use Rongun\Push\IosPayload;
use Rongun\Push\Message;
use Rongun\Push\PayLoad;
use Rongun\Push\Policy;

/**
 * 纸箱列表推送
 * Class SendRequest
 * @package Rongun\Request
 */
class SendRequest
{
    /**
     * @var Config
     */
    private $config;

    public $requestModel;

    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->requestModel = new HttpRequest($this->config);
    }

    public function send(Message $message, PayLoad $payLoad, Policy $policy, array $otherParams = [])
    {
        $otherParams['production_mode'] = $this->config->getProductionMode();
        $this->requestModel->post('api/send', array_merge(
            $message->getData(),
            ['payload' => $payLoad->getData()],
            ['policy' => $policy->getData()],
            $otherParams
        ));
        return [$this->requestModel->isOk(), $this->requestModel->getData()];
    }

    /**
     * 指定用户id推送
     * @param string $deviceToken
     * @return Message
     */
    private function getMessage(string $deviceToken): Message
    {
        $messageModel = Message::make();
        $messageModel->setType(Message::TYPE_UNI_CAST);
        $messageModel->setDeviceTokens($deviceToken);
        if (strpos($deviceToken, ",") !== false) {
            $messageModel->setType(Message::TYPE_LIST_CAST);
        }
        return $messageModel;
    }

    public function androidSend(string $type, string $deviceToken, $title, $text, $url = '', $otherParams = [], $ticker = '', $sign = '')
    {
        $message = $this->getMessage($deviceToken);
        $payload = AndroidPayLoad::make()
            ->setDisplayType($type)
            ->setTitle($title)
            ->setText($text)
            ->setTicker($ticker)
            ->setExtra($otherParams);
        if ($url) {
            $payload->setAfterOpen(AndroidPayLoad::OPEN_URL)->setAfterOpenParams($url);
        }
        $policy = Policy::make()->setOutBizNo($sign);
        return $this->send($message, $payload, $policy);
    }

    /**
     * 通知
     * @param string $deviceToken
     * @param $title
     * @param $text
     * @param string $url
     * @param array $otherParams
     * @param string $ticker
     * @param string $sign 唯一标识
     * @return array
     */
    public function androidNotification(string $deviceToken, $title, $text, $url = '', $otherParams = [], $ticker = '', $sign = '')
    {
        return $this->androidSend(AndroidPayLoad::TYPE_NOTIFICATION, $deviceToken, $title, $text, $url, $otherParams, $ticker, $sign);
    }

    /**
     * 消息
     * @param string $deviceToken
     * @param $title
     * @param $text
     * @param string $url
     * @param array $otherParams
     * @param string $ticker
     * @param string $sign
     * @return array
     */
    public function androidMessage(string $deviceToken, $title, $text, $url = '', $otherParams = [], $ticker = '', $sign = '')
    {
        return $this->androidSend(AndroidPayLoad::TYPE_MESSAGE, $deviceToken, $title, $text, $url, $otherParams, $ticker, $sign);
    }

    /**
     * ios 推送
     * @param string $deviceToken
     * @param $title
     * @param $text
     * @param string $url
     * @param array $otherParams
     * @param string $ticker
     * @param string $sign
     * @return array
     */
    public function iosSend(string $deviceToken, $title, $text, $url = '', $otherParams = [], $ticker = '', $sign = '')
    {
        $otherParams['url'] = $url;
        $message = $this->getMessage($deviceToken);
        $payload = IosPayload::make()
            ->setContentAvailable(true)
            ->setAlert($title, $text, $ticker)
            ->setOtherParams($otherParams)
            ->setBadge('+1');
        $policy = Policy::make()->setOutBizNo($sign);
        return $this->send($message, $payload, $policy);
    }
}
