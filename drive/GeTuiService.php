<?php
/**
 * Created by PhpStorm.
 * User: xing.chen
 * Date: 2018/3/17
 * Time: 0:00
 */

namespace xing\push\drive;

use xing\push\sdk\geTui\IGeTui;
use xing\push\sdk\geTui\igetui\IGtAppMessage;
use xing\push\sdk\geTui\igetui\IGtAPNPayload;
use xing\push\sdk\geTui\igetui\template\IGtNotificationTemplate;
use xing\push\sdk\geTui\igetui\IGtSingleMessage;
use xing\push\sdk\geTui\igetui\IGtTarget;

class GeTuiService extends \xing\push\core\BasePush implements \xing\push\core\PushInterface
{

    protected $config = [];
    protected $AppID;
    protected $AppSecret;
    protected $AppKey;
    protected $MasterSecret;
    const HOST = 'http://sdk.open.api.igexin.com/apiex.htm';
    protected $result;

    /**
     * 初始化
     * @param array $config 配置
     * @return GeTuiService
     */
    public static function init($config)
    {
        $class = new self();

        $class->config = $config;
        $class->AppKey = $config['AppKey'] ?? '';
        $class->AppID = $config['AppID'] ?? '';
        $class->AppSecret = $config['AppSecret'] ?? '';
        $class->MasterSecret = $config['MasterSecret'] ?? '';

        return $class;
    }

    // 发送广播
    public function sendAll()
    {
        $igt = new IGeTui(self::HOST, $this->AppKey, $this->MasterSecret);
        //定义透传模板，设置透传内容，和收到消息是否立即启动启用
        $template = $this->getTemplate();
        //$template = IGtLinkTemplateDemo();
        // 定义"AppMessage"类型消息对象，设置消息内容模板、发送的目标App列表、是否支持离线发送、以及离线消息有效期(单位毫秒)
        $message = new IGtAppMessage();
        $message->set_isOffline(true);
        $message->set_offlineExpireTime(10 * 60 * 1000);//离线时间单位为毫秒，例，两个小时离线为3600*1000*2
        $message->set_data($template);

        $appIdList = array($this->AppID);
        // 用户属性
        //$age = array("0000", "0010");


        //$cdt = new AppConditions();
        // $cdt->addCondition(AppConditions::PHONE_TYPE, $phoneTypeList);
        // $cdt->addCondition(AppConditions::REGION, $provinceList);
        //$cdt->addCondition(AppConditions::TAG, $tagList);
        //$cdt->addCondition("age", $age);

        $message->set_appIdList($appIdList);
        //$message->set_conditions($cdt->getCondition());

        $this->result = $igt->pushMessageToApp($message);
    }
    // 安卓 - 广播
    public function sendAllAndroid()
    {
        $this->sendAll();
    }
    // IOS - 广播
    public function sendAllIOS()
    {
        $this->sendAll();
    }
    /**
     * 单播：所有平台
     * @param string $device
     */
    public function sendOnealias($Alias)
    {
        $igt = new IGeTui(self::HOST, $this->AppKey, $this->MasterSecret);

        //消息模版：
        // 4.NotyPopLoadTemplate：通知弹框下载功能模板
        $template = $this->getTemplate();


        //定义"SingleMessage"
        $message = new IGtSingleMessage();

        $message->set_isOffline(true);//是否离线
        $message->set_offlineExpireTime(3600*12*1000);//离线时间
        $message->set_data($template);//设置推送消息类型
        //$message->set_PushNetWorkType(0);//设置是否根据WIFI推送消息，2为4G/3G/2G，1为wifi推送，0为不限制推送
        //接收方
        $target = new IGtTarget();
        $target->set_appId($this->AppID);
        //$target->set_clientId($device);
        $target->set_alias($Alias);
        $this->result = $igt->pushMessageToSingle($message, $target);
    }
    /**
     * 单播：所有平台
     * @param string $device
     */
    public function sendOne($device)
    {
        $igt = new IGeTui(self::HOST, $this->AppKey, $this->MasterSecret);

        //消息模版：
        // 4.NotyPopLoadTemplate：通知弹框下载功能模板
        $template = $this->getTemplate();


        //定义"SingleMessage"
        $message = new IGtSingleMessage();

        $message->set_isOffline(true);//是否离线
        $message->set_offlineExpireTime(3600*12*1000);//离线时间
        $message->set_data($template);//设置推送消息类型
        //$message->set_PushNetWorkType(0);//设置是否根据WIFI推送消息，2为4G/3G/2G，1为wifi推送，0为不限制推送
        //接收方
        $target = new IGtTarget();
        $target->set_appId($this->AppID);
        $target->set_clientId($device);
//    $target->set_alias(Alias);

        $this->result = $igt->pushMessageToSingle($message, $target);
    }

    /**
     * 安卓 - 单播
     * @param string $device 发送设备
     */
    public function sendOneAndroid($device)
    {
        $this->sendOne($device);
    }

    /**
     * IOS - 单播
     * @param string $device 发送设备
     */
    public function sendOneIOS($device)
    {
        $this->sendOne($device);
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
        return $this->result;
    }

    private function getTemplate()
    {
        $template =  new IGtNotificationTemplate();
        $template->set_appId($this->AppID);                   //应用appid
        $template->set_appkey($this->AppKey);                 //应用appkey
        $template->set_transmissionType(1);            //透传消息类型
        $template->set_transmissionContent("测试离线");//透传内容
        $template->set_title($this->title);      //通知栏标题
        $template->set_text($this->body);     //通知栏内容
//        $template->set_logo("");                       //通知栏logo
//        $template->set_logoURL("");                    //通知栏logo链接
        $template->set_isRing(true);                   //是否响铃
        $template->set_isVibrate(true);                //是否震动
        $template->set_isClearable(true);              //通知栏是否可清除$template->set_transmissionType(1);//透传消息类型

        if (!empty($this->extendedData)) {
            $extendData = json_encode($this->extendedData);
            $template->set_transmissionType(1);//透传消息类型
            $template->set_transmissionContent($extendData);//透传内容
        }

        return $template;
    }
}