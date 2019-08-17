## 友盟消息推送

### 安装

```text

composer require wujie/youmeng

```

### demo

#### 设置配置文件
```php
<?php
$config = new \Youmeng\Config\Config([
    'appKey' => '',
    'masterSecret' => '',
    'retryNum' => 1,//失败重试次数
    'production_mode' => 'true'
]);
```

#### 推送

```php
<?php

$comMessage = (new \Youmeng\Push\CommonMessage())
    ->setTitle("")
    ->setDesc("")
    ->setMessageType(\Youmeng\Push\Message::TYPE_CUSTOMIZE_CAST)
    ->setMessageData($alidateId)
    ->setOtherParams([]);

$sendRequest = new \Youmeng\Request\SendRequest($config);
$data = $sendRequest->androidMessage($signId, $comMessage);

```


#### 查询推送状态

```php

<?php
(new \Youmeng\Request\StatusRequest($config))->status($taskId);

```

#### 取消定时任务

```php
<?php
(new \Youmeng\Request\CancelRequest($config))->cancel($taskId);

```


#### 上传任务

```php

<?php

(new \Youmeng\Request\UpdateRequest($config))->update($content);
```
