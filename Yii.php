<?php
/**
 * Created by PhpStorm.
 * User: xing.chen
 * Date: 2018/1/24
 * Time: 10:37
 */

namespace xing\push;


use xing\push\core\BasePush;
use xing\push\core\PushFactory;
use xing\push\core\PushInterface;

/**
 * Class Yii
 * @property \xing\push\drive\UmengService|\xing\push\drive\XingeService|\xing\push\drive\GetuiService $drive
 * @property array $config
 * @property string $driveName
 * @package xing\push
 */
class Yii extends BasePush implements PushInterface
{
    public $driveName;
    public $drive;
    public $config;

    protected function initDrive()
    {
        if (!empty($this->drive)) return $this->drive;
        $this->drive = PushFactory::getInstance($this->driveName)::init($this->config);
        $this->drive->title = $this->title;
        $this->drive->body = $this->body;
        $this->drive->extendedData = $this->extendedData;
    }

    // 发送广播
    public function sendAll()
    {
        $this->initDrive();
        $this->drive->sendAll();
    }
    // 安卓 - 广播
    public function sendAllAndroid()
    {
        $this->initDrive();
        return $this->drive->sendAllAndroid();
    }
    // IOS - 广播
    public function sendAllIOS()
    {
        $this->initDrive();
        return $this->drive->sendAllIOS();
    }

    /**
     * 单播：所有平台
     * @param string $device
     */
    public function sendOne($device)
    {
        $this->initDrive();
        return $this->drive->sendOne($device);
    }

    /**
     * 安卓 - 单播
     * @param string $device 发送设备
     */
    public function sendOneAndroid($device)
    {
        $this->initDrive();
        return $this->drive->sendOneAndroid($device);
    }

    /**
     * IOS - 单播
     * @param string $device 发送设备
     */
    public function sendOneIOS($device)
    {
        $this->initDrive();
        return $this->drive->sendOneIOS($device);
    }

    // 发送组播
    public function sendGroup()
    {
        $this->initDrive();
        return $this->drive->sendGroup();
    }
    // 安卓 - 组播
    public function sendGroupAndroid()
    {
        $this->initDrive();
        return $this->drive->sendGroupAndroid();
    }
    // IOS - 组播
    public function sendGroupIOS()
    {
        $this->initDrive();
        return $this->drive->sendGroupIOS();
    }

    public function getError()
    {
        return $this->drive->getError();
    }

    public function getResult()
    {
        return $this->drive->getResult();
    }
}