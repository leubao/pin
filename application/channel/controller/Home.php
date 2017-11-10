<?php
// +----------------------------------------------------------------------
// | LubRDF 渠道首页控制器
// +----------------------------------------------------------------------
// | Copyright (c) 2017 http://www.leubao.com, All rights reserved.
// +----------------------------------------------------------------------
// | Author: zhoujing <admin@leubao.com>
// +----------------------------------------------------------------------
namespace app\channel\controller;
use app\common\controller\Channel;
class Home extends Channel
{
	/**
	 * 首页控制器
	 * @Company  承德乐游宝软件开发有限公司
	 * @Author   zhoujing      <zhoujing@leubao.com>
	 * @DateTime 2017-11-06
	 */
    public function index()
    {
    	//dump(url('channel/index/login','','html',true));
        return $this->fetch();
    }
}
