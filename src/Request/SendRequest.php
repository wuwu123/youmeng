<?php


namespace Youmeng\Request;

use Youmeng\Config\Config;
use Youmeng\Push\AndroidPayLoad;
use Youmeng\Push\CommonMessage;
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
        $this->requestModel = new HttpRequest($config);
        $this->safety = new Safety($config);
    }

    public function send(Message $message, PayLoad $payLoad, Policy $policy, array $otherParams = [])
    {
        $otherParams['production_mode'] = $this->config->getProductionMode();
        [$checkBool, $errormsg] = $this->safety->checkKey('api/send', $message->getData());
        if (!$checkBool) {
            return [$checkBool, $errormsg ?? "安全规则限制 ，相同的消息频繁发送"];
        }
        $postData = $message->getData();
        if ($payLoad->getData()) {
            $postData['payload'] = $payLoad->getData();
        }
        if ($policy->getData()) {
            $postData['policy'] = $policy->getData();
        }
        if ($otherParams) {
            $postData = array_merge($postData, $otherParams);
        }
        $this->requestModel->post('api/send', $postData);
        return [$this->requestModel->isOk(), $this->requestModel->getData()];
    }

    /**
     * @param CommonMessage $message
     * @return Message
     * @throws \Exception
     */
    private function getMessage(CommonMessage $message): Message
    {
        $messageModel = Message::make()->init($message->getMessageType(), $message->getMessageData());
        return $messageModel;
    }

    public function androidSend($sign, string $type, CommonMessage $comMessage)
    {
        $message = $this->getMessage($comMessage);
        $payload = AndroidPayLoad::make()
            ->setDisplayType($type)
            ->setTitle($comMessage->getTitle())
            ->setText($comMessage->getDesc())
            ->setTicker($comMessage->getTicker())
            ->setExtra($comMessage->getOtherParams())
            ->setCustom($comMessage->getAndroidCustom());
        if ($comMessage->getUrl()) {
            $payload->setAfterOpen(AndroidPayLoad::OPEN_URL)->setAfterOpenParams($comMessage->getUrl());
        }
        $policy = Policy::make()->setOutBizNo($sign);
        return $this->send($message, $payload, $policy);
    }

    /**
     * 通知
     * @param $sign
     * @param CommonMessage $message
     * @return array
     */
    public function androidNotification($sign, CommonMessage $message)
    {
        return $this->androidSend($sign, AndroidPayLoad::TYPE_NOTIFICATION, $message);
    }

    /**
     * @param $sign
     * @param CommonMessage $message
     * @return array
     */
    public function androidMessage($sign, CommonMessage $message)
    {
        return $this->androidSend($sign, AndroidPayLoad::TYPE_MESSAGE, $message);
    }

    /**
     * ios 推送
     * @param $sign
     * @param CommonMessage $commonMessage
     * @return array
     * @throws \Exception
     */
    public function iosSend($sign, CommonMessage $commonMessage)
    {
        $otherParams['url'] = $commonMessage->getUrl();
        $message = $this->getMessage($commonMessage);
        $payload = IosPayload::make()
            ->setContentAvailable(true)
            ->setAlert($commonMessage->getTitle(), $commonMessage->getDesc(), $commonMessage->getTicker())
            ->setOtherParams($otherParams)
            ->setBadge('+1');
        $policy = Policy::make()->setOutBizNo($sign);
        return $this->send($message, $payload, $policy);
    }
}
