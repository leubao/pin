<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

return [
    // 生成应用公共文件
    '__file__' => ['common.php', 'config.php', 'database.php'],
    //公众配置
    'common'        =>  [
        '__file__'   => ['common.php'],
        '__dir__'    => ['controller', 'model'],
        'controller' => ['LubRDF', 'Base', 'ApiBase'],
        'model'      => ['User'],
       // 'libs'       => ['util/Encrypt']
    ],
    // 定义demo模块的自动生成 （按照实际定义的文件名生成）
    'channel'     => [
        '__file__'   => ['common.php'],
        '__dir__'    => ['behavior', 'controller', 'model', 'service','view'],
        'controller' => ['Index', 'Channel', 'Setting', 'Order', 'Report', 'Product'],
        'model'      => ['User', 'Crm', 'Order', 'Menu', 'CrmRecharge'],
        'service'    => ['Order', 'Report', ],
        'view'       => ['index/index', 'channel/index', 'order/index', 'channel/', 'report/rakeback', 'report/today'],
    ],
    // 其他更多的模块定义
];
