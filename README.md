# push
设计概要：

PHP版本：信鸽PHP7以上，友盟5.3以上

目前驱动有：信鸽，友盟

工厂模式：可根据配置随时切换第三方推送（本人的所有包库都是工厂模式）

接口规范

目前支持的框架：Yii

不需要依赖框架运行：是
##### 注：友盟已完成测试无误。 信鸽运行测试没有问题，但是否能到达手机未测试。
### 安装
composer require xing.chen/push dev-master

# 使用示例
```php
<?php
//  ***  初始化驱动

$configUmeng = [
    'android' => [
        'appKey' => 'appKey',
        'appMasterSecret' => 'appMasterSecret'
    ],
    'IOS' => [
        'appKey' => 'appKey',
        'appMasterSecret' => 'appMasterSecret'  
    ]
];

$configXinge = [
   'expireTime' => 86400,
    'android' => [
       'accessId' => 'accessId',
       'secret_key' => 'secret_key',
    ],
    'IOS' => [
       'accessId' => 'accessId',
       'secret_key' => 'secret_key',
    ]
];



// ---------------------- YII框架 --------------------

// 需要先在components设置：
// 友盟
$driveName = 'Umeng';
$configYii = $configUmeng;

// 信鸽
$driveName = 'xingge';
$configYii = $configXinge;

'components'=> [
        'push' => [
            'class' => 'xing\push\Yii',
            'driveName' => $driveName, // 使用哪个推送驱动名称
            // 驱动里的配置
            'config' => $configYii
        ],
];

// 设置标题和消息、自定义参数
$push = Yii::$app->push
        ->setTitle('标题')
        ->setBody('消息正文')
        ->setExtendedData(['a' => 1, 'b' => 2]); // 自定义扩展参数;
        
        
// -------------------- 独立运行：工厂模式 ---------------------

// 设置标题和消息、自定义参数
$push = \xing\push\core\PushFactory::getInstance('Umeng或xinge')::init($configUmeng)
        ->setTitle('标题')
        ->setBody('消息正文')
        ->setExtendedData(['a' => 1, 'b' => 2]);// 自定义扩展参数

// -------------------- 独立运行：友盟 ---------------------
// 设置标题和消息、自定义参数
$push = \xing\push\drive\UmengService::init($configUmeng)
        ->setTitle('标题')
        ->setBody('消息正文')
        ->setExtendedData(['a' => 1, 'b' => 2]); // 自定义扩展参数
        
// -------------------- 独立运行：信鸽 ---------------------
// 设置标题和消息、自定义参数
$push = \xing\push\drive\UmengService::init($config)
        ->setTitle('标题')
        ->setBody('消息正文')
        ->setExtendedData(['a' => 1, 'b' => 2]); // 自定义扩展参数
        
        
//   ***************  发送方法  **********   
# 广播：所有平台
$push->sendAll();
# 广播：安卓
$push->sendAllAndroid();
# 广播：IOS
$push->sendAllIOS();

# 单播：所有平台
$push->sendOne('设备码');
# 单播：安卓
$push->sendOneAndroid('设备码');
# 单播：IOS
$push->sendOneIOS('设备码');

```