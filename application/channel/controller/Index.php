<?php
// +----------------------------------------------------------------------
// | LubRDF 渠道公用控制器
// +----------------------------------------------------------------------
// | Copyright (c) 2017 http://www.leubao.com, All rights reserved.
// +----------------------------------------------------------------------
// | Author: zhoujing <admin@leubao.com>
// +----------------------------------------------------------------------
namespace app\channel\controller;
use app\common\controller\LubRDF;
use app\common\libs\service\Partner;
class Index extends LubRDF
{
	/**
	 * 首页控制器
	 * @Company  承德乐游宝软件开发有限公司
	 * @Author   zhoujing      <zhoujing@leubao.com>
	 * @DateTime 2017-11-06
	 */
    public function index()
    {
        return $this->fetch();
    }
    /**
     * 系统登录
     * @Company  承德乐游宝软件开发有限公司
     * @Author   zhoujing      <zhoujing@leubao.com>
     * @DateTime 2017-11-06
     * @return   [type]        [description]
     */
    public function login()
    {
    	$partner = new Partner();
    	if($partner->isLogin()){
			$this->redirect('channel/home/index');
		}
    	return $this->fetch();
    }
    //退出登陆
    public function logout() {
    	$partner = new Partner();
        if ($partner->logout()) {
            $this->success('注销成功！', url("channel/index/login"));
        }
    }
    /**
     * @Company  承德乐游宝软件开发有限公司
     * @Author   zhoujing      <zhoujing@leubao.com>
     * @DateTime 2017-11-06
     * @return   [type]                              [description]
     */
    public function dologin()
    {
    	$partner = new Partner();
    	if($partner->login($this->param['username'], $this->param['password'])){
    		return json(['status'=>true,'code'=>10001,'msg'=>'登录成功']);
    	}else{
    		return json(['status'=>false,'code'=>10003,'msg'=>$partner->error]);
    	}
    	
    }
}
