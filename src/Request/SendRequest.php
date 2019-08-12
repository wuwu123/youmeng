<?php


namespace Youmeng\Request;

use Youmeng\Config\Config;
use Youmeng\Push\AndroidPayLoad;
use Youmeng\Push\IosPayload;
use Youmeng\Push\Message;
use Youmeng\Push\PayLoad;
use Youmeng\Push\Policy;
use Youmeng\Safety\Safety;

/**
 * 纸箱列表推送
 * Class SendRequest
 * @package Youmeng\Request
 */
class SendRequest
{
    /**
     * @var Config
     */
    private $config;

    public $requestModel;

    /**
     * @var Safety
     */
    private $safety;

    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->requestModel = new HttpRequest($this->config);
        $this->safety = new Safety($this->config);
    }

    public function send(Message $message, PayLoad $payLoad, Policy $policy, array $otherParams = [])
    {
        $otherParams['production_mode'] = $this->config->getProductionMode();
        [$checkBool, $errormsg] = $this->safety->checkKey('api/send', $message->getData());
        if (!$checkBool) {
            return [$checkBool, $errormsg ?? "安全规则限制 ，相同的消息频繁发送"];
        }
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
     * @param string $sign 消息唯一标识
     * @return array
     */
    public function androidMessage($sign, string $deviceToken, $title, $text, $url = '', $otherParams = [], $ticker = '')
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
     * @param string $sign 消息唯一标识
     * @param string $description
     * @return array
     */
    public function iosSend($sign, string $deviceToken, $title, $text, $url = '', $otherParams = [], $ticker = '', $description = '')
    {
        $otherParams['url'] = $url;
        $message = $this->getMessage($deviceToken);
        $payload = IosPayload::make()
            ->setContentAvailable(true)
            ->setAlert($title, $text, $ticker)
            ->setOtherParams($otherParams)
            ->setBadge('+1');
        $policy = Policy::make()->setOutBizNo($sign);
        return $this->send($message, $payload, $policy, ['description' => $description]);
    }
}
