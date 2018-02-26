<?php

namespace hollisho\applet;


/**
 * Class Session
 * @package hollisho\applet
 * @author Hollis Ho
 */
class Session
{
    protected $appType;
    protected $data;
    protected $user;

    public function __construct($appType, array $data = [], array $user = [])
    {
        $this->appType = $appType;
        $this->data = $data;
        $this->user = $user;
    }

    public function get($key, $default=null)
    {
        return $this->has($key) ? $this->data[$key] : $default;
    }

    public function has($key)
    {
        return array_key_exists($key, $this->data);
    }

    public function getOpenid()
    {
        return $this->get('openid');
    }
    
    public function getSessionKey()
    {
        return $this->get('session_key');
    }

    public function getUnionid()
    {
        return $this->get('unionid');
    }

    public function getExpiresIn()
    {
        return $this->get('expires_in');
    }

    public function getData()
    {
        return $this->data;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

}