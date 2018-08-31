<?php

namespace kuangjy\JPush;

use JPush\Client;
use JPush\Config;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\di\NotInstantiableException;

/**
 * Class JPush
 * @package kuangjy\JPush
 *
 * @method \JPush\PushPayload push()
 * @method \JPush\ReportPayload report()
 * @method \JPush\DevicePayload device()
 * @method \JPush\SchedulePayload schedule()
 *
 * @method string getAuthStr()
 * @method int getRetryTimes()
 * @method string getLogFile()
 * @method bool is_group()
 * @method string makeURL(string $key)
 */
class JPush extends Component
{
    /**
     * @var \JPush\Client
     */
    private $client;

    /**
     * @var string
     */
    public $key;
    /**
     * @var string
     */
    public $secret;
    /**
     * @var string
     */
    public $logPath = null;
    /**
     * @var int
     */
    public $retryTimes = Config::DEFAULT_MAX_RETRY_TIMES;
    /**
     * @var string
     */
    public $zone = null;

    /**
     * @throws InvalidConfigException
     * @throws NotInstantiableException
     */
    public function init()
    {
        parent::init();
        if (empty($this->key) || empty($this->secret)) {
            throw new InvalidConfigException("You must configure your correct SMS agents' configurations and schemes.");
        }
        $client = new Client($this->key, $this->secret, $this->logPath, $this->retryTimes, $this->zone);
        if (!$client instanceof Client) {
            throw new NotInstantiableException(Client::class);
        }
        $this->client = $client;
    }

    public function __call($name, $params)
    {
        if (method_exists($this, $name)) {
            return call_user_func_array([$this->client, $name], $params);
        }

        return parent::__call($name, $params);
    }

}