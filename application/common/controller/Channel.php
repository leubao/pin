<?php
// +----------------------------------------------------------------------
// | LubRDF 渠道基础控制器
// +----------------------------------------------------------------------
// | Copyright (c) 2017 http://www.leubao.com, All rights reserved.
// +----------------------------------------------------------------------
// | Author: zhoujing <admin@leubao.com>
// +----------------------------------------------------------------------
namespace app\common\controller;
use app\common\controller\LubRDF;
use app\common\libs\service\Partner;
class Channel extends LubRDF
{
	public function _initialize()
	{
		//登录验证 权限验证
		$partner = new Partner();
		if(!$partner->isLogin()){
			$this->redirect('channel/index/login');
		}
	}
}