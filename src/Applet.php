<?php

namespace hollisho\applet;

use hollisho\applet\Decrypt\AppletDecrypt;
use yii\base\Component;
use yii\web\HttpException;
use yii\httpclient\Client;

/**
 * Class Applet
 * @package hollisho\applet
 * @author Hollis Ho
 */
class Applet extends Component
{
    /**
     * @var string
     */
    public $appid;
    /**
     * @var string
     */
    public $secret;

    /**
     * @var string
     */
    public $appType = 'applet';

    /**
     * @var integer
     */
    public $storageDuration = 7200;

    /**
     * @var Client
     */
    private $client;
    /**
     * @var string
     */
    private $baseUrl = 'https://api.weixin.qq.com/sns';

    /**
     * @var Session
     */
    private $session;

    /**
     * @var Storage
     */
    private $_storage;


    /**
     * Get session_key from server
     * 
     * @param  $code
     * @return $this
     * @throws HttpException
     */
    public function makeSession($code)
    {
        $response = $this->getClient()->createRequest()
            ->setUrl($this->getSessionKeyUrl())
            ->setMethod('get')
            ->setData([
                'appid' => $this->appid,
                'secret' => $this->secret,
                'js_code' => $code,
                'grant_type' => 'authorization_code'
            ])->send();
        $result = $response->getData();
        if (isset($result['errcode'])) {
            throw new HttpException(500, $result['errmsg']);
        }

        return $this->setSession($code, $result);
    }

    /**
     * Get Session from Storage
     * @param $code
     * @return mixed
     */
    public function findSession($code, $keepLive = false) {
        $storage = $this->getStorage($code);
        $session = $storage->get($code);
        if ($session && $keepLive) {
            $storage->set($session);
            $this->session = $session;
        }
        return $this;
    }

    public function setSession($code, $data, $user = [])
    {
        $this->session = new Session($this->appType, $data, $user);
        $this->getStorage($code)->set($this->session);
        return $this;
    }

    public function getSession()
    {
        return $this->session;
    }

    /**
     * instance decrypt
     */
    public function decrypt()
    {
        return new AppletDecrypt($this->appid, $this->session->getSessionKey());
    }

    public function __call($method, $arguments)
    {
        $decrypt = $this->decrypt();
        if(method_exists($decrypt, $method)){
            return call_user_func_array([$decrypt, $method], $arguments);
        }

        throw new \BadMethodCallException("Method [$method] does not exist.");
    }

    /**
     * Get session_key server url
     * 
     * @return string
     */
    protected function getSessionKeyUrl()
    {
        return $this->baseUrl.'/jscode2session';
    }

    /**
     * Get client instance
     *
     * @return Client
     */
    protected function getClient()
    {
        return $this->client?:new Client([
            'transport' => 'yii\httpclient\CurlTransport'
        ]);
    }

    /**
     * Set client instance
     * 
     * @param  Client $client
     * @return $this
     */
    public function setClient(Client $client)
    {
        $this->client = $client;
        
        return $this;
    }


    /**
     * Get Storage instance
     *
     * @param $key
     * @return Storage
     */
    public function getStorage($key) {
        if (!$this->_storage) {
            $this->_storage = new Storage([
                'key' => $key,
                'duration' => $this->storageDuration
            ]);
        }

        return $this->_storage;
    }
}


