<?php

namespace hollisho\applet\Model;

/**
 * Class UserInfo
 * @package hollisho\applet\Model
 * @author Hollis Ho
 */
class UserInfo
{
    protected $data;

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public function get($key, $default=null)
    {
        return $this->has($key) ? $this->data[$key] : $default;
    }

    public function has($key)
    {
        return array_key_exists($key, $this->data);
    }

    public function getOpenId()
    {
        return $this->get('openId');
    }

    public function getNickName()
    {
        return $this->get('nickName');
    }

    public function getGender()
    {
        return $this->get('gender');
    }

    public function getCity()
    {
        return $this->get('city');
    }

    public function getProvince()
    {
        return $this->get('province');
    }

    public function getCountry()
    {
        return $this->get('country');
    }

    public function getAvatarUrl()
    {
        return $this->get('avatarUrl');
    }

    public function getUnionId()
    {
        return $this->get('unionId');
    }
}