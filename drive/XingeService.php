<?php
/**
 * Created by PhpStorm.
 * User: xing.chen
 * Date: 2018/1/13
 * Time: 19:43
 * @author xing.chen
 * @ex
 */

namespace xing\push\drive;


use xing\push\core\PushInterface;
use xing\push\sdk\xinge\XingeApp;
use xing\push\sdk\xinge\Message;
use xing\push\sdk\xinge\MessageIOS;
use xing\push\sdk\xinge\Style;
use xing\push\sdk\xinge\ClickAction;
use xing\push\sdk\xinge\TimeInterval;

/**
 * Class XingeService
 * @property array $config
 * @property Message|MessageIOS $mess
 * @package xing\push\drive
 */
class XingeService extends \xing\push\core\BasePush implements PushInterface
{

    private $config = [];
    private $mess;

    /**
     * 初始化
     * @param array $config 配置
     * @return XingeService
     */
    public static function init($config)
    {
        $class = new self();

        $class->config = $config;

        return $class;
    }


    /**
     * 执行发送
     * @param array $config 安卓|IOS的应用信息配置
     * @return XingeApp
     */
    protected function sendInit($config)
    {

        $acceptTime1 = new TimeInterval(0, 0, 23, 59);
        $this->mess->addAcceptTime($acceptTime1);
        $this->mess->setCustom($this->extendedData);
        $this->mess->setExpireTime($this->config['expireTime']);

        return new XingeApp($config['accessId'], $config['secret_key']);
    }

    /**
     * 获取IOS驱动
     * @return MessageIOS
     */
    protected function getIosMess()
    {
        $this->mess = new MessageIOS();
        $this->mess->setAlert($this->title);
        $this->mess->setBadge(1);
        $this->mess->setSound("beep.wav");
//        $raw = '{"xg_max_payload":1,"accept_time":[{"start":{"hour":"20","min":"0"},"end":{"hour":"23","min":"59"}}],"aps":{"alert":"="}}';
//        $this->mess->setRaw($raw);
        return $this->mess;
    }

    /**
     * 获取安卓驱动
     * @return Message
     */
    protected function getAndroidMess()
    {
        $this->mess = new Message();
        $this->mess->setType(Message::TYPE_MESSAGE);
        $this->mess->setTitle($this->title);
        $this->mess->setContent($this->body);
        $this->mess->setStyle($this->getStyle());
        return $this->mess;
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
        $this->getAndroidMess();
        return $this->sendInit($this->config['android'])->PushAllDevices(0, $this->mess);
    }
    // IOS - 广播
    public function sendAllIOS()
    {
        $this->getIosMess();
        return $this->sendInit($this->config['IOS'])->PushAllDevices(0, $this->mess);
    }

    /**
     * 单播：所有平台
     * @param string $device
     */
    public function sendOne($device)
    {
        $this->sendOneAndroid($device);
        $this->sendOneIOS($device);
    }

    /**
     * 安卓 - 单播
     * @param string $device 发送设备
     */
    public function sendOneAndroid($device)
    {
        $this->getAndroidMess();
        return $this->sendInit($this->config['android'])->PushSingleDevice($device, $this->mess);
    }

    /**
     * IOS - 单播
     * @param string $device 发送设备
     */
    public function sendOneIOS($device)
    {
        $this->getIosMess();
        return $this->sendInit($this->config['IOS'])->PushSingleDevice($device, $this->mess);
    }

    // 发送组播
    public function sendGroup()
    {

    }
    // 安卓 - 组播
    public function sendGroupAndroid()
    {

    }
    // IOS - 组播
    public function sendGroupIOS()
    {

    }

    public function getError()
    {
    }

    public function getResult()
    {
    }

    /**
     * 设置样式
     * @return Style
     */
    protected function getStyle()
    {
        $style = $this->config['style'] ?? [];
        $builderId = $style['builderId'] ?? 0;
        $ring = $style['ring'] ?? 0;
        $vibrate = $style['vibrate'] ?? 0;
        $clearable = $style['clearable'] ?? 0;
        $nId = $style['nId'] ?? 0;
        $lights = $style['lights'] ?? 0;
        $iconType = $style['iconType'] ?? 0;
        $styleId = $style['styleId'] ?? 0;
        #含义：样式编号0，响铃，震动，不可从通知栏清除，不影响先前通知
        return new Style($builderId, $ring, $vibrate , $clearable, $nId, $lights, $iconType, $styleId);
    }

    public function sendOnealias($alias)
    {
        // TODO: Implement sendOnealias() method.
    }
}