# push
设计概要：

使用工厂模式，接口规范

目前驱动有：友盟

目前支持的框架：Yii

不依赖框架运行：是

### 安装
composer xing.chen/push dev-master

# 使用示例
```php
<?php
$config = [
    'appKey' => 'appKey',
    'appMasterSecret' => 'appMasterSecret'
];
//  ***  初始化驱动

# YII框架
// 需要先在设置：

'components'=> [
        'push' => [
            'class' => 'xing\push\Yii',
            'drive' => 'Umeng', // 使用哪个推送驱动名称
            // 驱动里的配置
            'config' => [
                'appKey' => 'appKey',
                'appMasterSecret' => 'appMasterSecret',
            ]
        ],
];
$push = Yii::$app->push
        ->setTitle('标题')
        ->setBody('消息正文')
        ->setExtendedData(['a' => 1, 'b' => 2]); // 自定义扩展参数;
        
# 不依赖框架
$push = \xing\push\drive\UmengService::init($config)
        ->setTitle('标题')
        ->setBody('消息正文')
        ->setExtendedData(['a' => 1, 'b' => 2]); // 自定义扩展参数
        
# 广播所有平台
$push->sendAll();
# 广播-安卓
$push->sendAllAndroid();
# 广播-IOS
$push->sendAllIOS();

# 单播所有平台
$push->sendOne('设备码');
# 单播安卓
$push->sendOneAndroid('设备码');
# 单播IOS
$push->sendOneIOS('设备码');

```