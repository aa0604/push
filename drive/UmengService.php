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
use xing\push\sdk\uMeng\notification\AndroidNotification;
use xing\push\sdk\uMeng\notification\IOSNotification;

/**
 * Class UmengService
 * @property string $appKey
 * @property string $appMasterSecret
 * @property string $timestamp
 * @property AndroidNotification|IOSNotification $sdk
 * @property string $platform
 * @property array $config
 * @package xing\push\drive
 */
class UmengService extends \xing\push\core\BasePush implements PushInterface
{

    protected $appKey;
    protected $appMasterSecret;
    protected $timestamp;
    // 测试模式开启
    protected $test = false;

    protected $sdk;
    protected $platform;
    private $config = [];

    /**
     * 初始化
     * @param array $config 配置
     * @return UmengService
     */
    public static function init($config)
    {
        $class = new self();

        $class->config = $config;
        $class->timestamp = strval(time());
        // 测试模式开启
        isset($config['test']) && $class->test = $config['test'];

        return $class;
    }

    /**
     * 设置 sdk
     * @param string $type
     * @return AndroidNotification|IOSNotification
     */
    public function setSdk($type = 'android')
    {
        if (!empty($this->sdk)) return $this->sdk;
        $this->platform = $type;
        $this->sdk = $type == 'android' ? new AndroidNotification() : new IOSNotification();
        $this->sdk->setPredefinedKeyValue("timestamp",        $this->timestamp);
        $this->sdk->setPredefinedKeyValue("production_mode", !empty($this->test));

        # 安卓和IOS的区别配置
        $config = $type == 'android' ? $this->config['android'] : $this->config['IOS'];
        $this->sdk->setAppMasterSecret($config['appMasterSecret']);
        $this->sdk->setPredefinedKeyValue("appkey",           $config['appKey']);

        return $this->sdk;
    }

    /**
     * 执行发送
     */
    protected function send()
    {
        # 设置必选参数
        if ($this->platform == 'android') {
            $this->sdk->setPredefinedKeyValue("ticker",           $this->title);
            $this->sdk->setPredefinedKeyValue("title",            $this->title);
            $this->sdk->setPredefinedKeyValue("text",             $this->body);
            $this->sdk->setPredefinedKeyValue("after_open",       'go_app'); // 后续行为，其他推送没有这个东西，所以不做成为专门动作，统一使用该值
        } else {
            $this->sdk->setPredefinedKeyValue("alert", $this->title);
        }

        # 设置扩展参数
        if (!empty($this->extendedData)) {
            # 安卓
            if ($this->platform == 'android') {
                foreach ($this->extendedData as $k => $v) $this->sdk->setExtraField($k, $v);
            } else {
                # IOS 扩展参数以数组形式全放在extra变量里
                $this->sdk->setCustomizedField('extra', $this->extendedData);
            }
        }
        $this->sdk->send();
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
        $this->setSdk('android');
        $this->sdk->data["type"] = "broadcast";
        $this->send();
    }
    // IOS - 广播
    public function sendAllIOS()
    {
        $this->setSdk('IOS');
        $this->sdk->data["type"] = "broadcast";
        $this->send();
    }

    /**
     * 单播：所有平台
     * @param string $device
     */
    public function sendOne(string $device)
    {
        $this->sendOneAndroid($device);
        $this->sendOneIOS($device);
    }

    /**
     * 安卓 - 单播
     * @param string $device 发送设备
     */
    public function sendOneAndroid(string $device)
    {
        $this->setSdk('android');
        $this->sdk->data["type"] = "unicast";
        // 单播设备：设置默认为null
        $this->sdk->data['device_tokens'] = null;
        $this->sdk->setPredefinedKeyValue("device_tokens",    $device);
        $this->send();
    }

    /**
     * IOS - 单播
     * @param string $device 发送设备
     */
    public function sendOneIOS(string $device)
    {
        $this->setSdk('IOS');
        $this->sdk->data["type"] = "unicast";
        // 单播设备：设置默认为null
        $this->sdk->data['device_tokens'] = null;
        $this->sdk->setPredefinedKeyValue("device_tokens",    $device);
        $this->send();
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
}