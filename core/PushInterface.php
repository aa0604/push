<?php
/**
 * Created by PhpStorm.
 * User: xing.chen
 * Date: 2018/1/13
 * Time: 19:42
 */

namespace xing\push\core;


interface PushInterface
{

    // 发送广播
    public function sendAll();
    // 安卓 - 广播
    public function sendAllAndroid();
    // IOS - 广播
    public function sendAllIOS();

    // 发送组播
    public function sendGroup();
    // 安卓 - 组播
    public function sendGroupAndroid();
    // IOS - 组播
    public function sendGroupIOS();

    // 单播
    public function sendOne($device);
    // 单播 别名
    public function sendOnealias($alias);
    // 安卓 - 单播
    public function sendOneAndroid($device);
    // IOS - 单播
    public function sendOneIOS($device);
    // 获取错误信息
    public function getError();
    // 获取访问接口的结果
    public function getResult();
}