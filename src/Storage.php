<?php

namespace hollisho\applet;

use yii\base\Component;

/**
 * Class Storage
 * @package hollisho\applet
 * @author Hollis Ho
 */
class Storage extends Component
{
    private $_key;
    private $_cache;
    private $_duration = 7200;

    public function init() {
        $this->_cache = \Yii::$app->getCache()->get($this->_key);
    }

    public function set($session) {
        $this->_cache = $session;
        return \Yii::$app->getCache()->set($this->_key, $this->_cache, $this->_duration);
    }

    public function get() {
        return \Yii::$app->getCache()->get($this->_key);
    }

    public function setKey($key) {
        $this->_key = $key;
    }

    public function setDuration($duration) {
        $this->_duration = $duration;
    }
}