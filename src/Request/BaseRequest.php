<?php


namespace Youmeng\Request;

use Youmeng\Config\Config;
use Youmeng\Safety\Safety;

class BaseRequest
{
    /**
     * @var Config
     */
    protected $config;

    protected $requestModel;

    /**
     * @var Safety
     */
    protected $safety;

    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->requestModel = new HttpRequest($config);
        $this->safety = new Safety($config);
    }
}
