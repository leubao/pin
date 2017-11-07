<?php
// +----------------------------------------------------------------------
// | LubRDF 基础控制器
// +----------------------------------------------------------------------
// | Copyright (c) 2017 http://www.leubao.com, All rights reserved.
// +----------------------------------------------------------------------
// | Author: zhoujing <admin@leubao.com>
// +----------------------------------------------------------------------
namespace app\common\controller;
use think\Request;
use think\Session;
use app\common\service\ReturnCode;
class LubRDF extends \think\Controller
{
	// 初始化
    public function _initialize()
    {
    	parent::_initialize();
    	//dump($_SERVER);
    	/*防止跨域     
        header('Access-Control-Allow-Origin: '.$_SERVER['HTTP_ORIGIN']);
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');$request->header['authorization']
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, authKey, sessionId");
    	$this->app = 'wqeqwe';*/ 
        $request = Request::instance();
    	$this->param = Request::instance()->param();
        //dump($this->param);
        //Session::set('name.item','thinkphp');
        //dump(Session::get('name.item'));
        //网站初始化
        $this->initWeb();
    }
    public function initWeb()
    {
    	# code...
    }
}