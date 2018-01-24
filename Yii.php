<?php
/**
 * Created by PhpStorm.
 * User: xing.chen
 * Date: 2018/1/24
 * Time: 10:37
 */

namespace xing\push;


use xing\push\core\PushFactory;
use xing\push\core\PushInterface;

/**
 * Class Yii
 * @property \xing\push\drive\UmengService $drive
 * @property array $config
 * @package xing\push
 */
class Yii implements PushInterface
{
    public $drive;
    public $config;

    public function __construct()
    {
        $this->drive = PushFactory::getInstance($this->drive)::init($this->config);
    }


    // 发送广播
    public function sendAll()
    {
        $this->sendAllAndroid();
        $this->sendAllIOS();
    }
    // 安卓 - 广播
    public function sendAllAndroid()
    {
        return $this->drive->sendAllAndroid();
    }
    // IOS - 广播
    public function sendAllIOS()
    {
        return $this->drive->sendAllIOS();
    }

    /**
     * 单播：所有平台
     * @param string $device
     */
    public function sendOne(string $device)
    {
        return $this->drive->sendOne($device);
    }

    /**
     * 安卓 - 单播
     * @param string $device 发送设备
     */
    public function sendOneAndroid(string $device)
    {
        return $this->drive->sendOneAndroid($device);
    }

    /**
     * IOS - 单播
     * @param string $device 发送设备
     */
    public function sendOneIOS(string $device)
    {
        return $this->drive->sendOneIOS($device);
    }

    // 发送组播
    public function sendGroup()
    {
        return $this->drive->sendGroup();
    }
    // 安卓 - 组播
    public function sendGroupAndroid()
    {
        return $this->drive->sendGroupAndroid();
    }
    // IOS - 组播
    public function sendGroupIOS()
    {
        return $this->drive->sendGroupIOS();
    }
}