<?php
// +----------------------------------------------------------------------
// | LubRDF 用户操作类
// +----------------------------------------------------------------------
// | Copyright (c) 2017 http://www.leubao.com, All rights reserved.
// +----------------------------------------------------------------------
// | Author: zhoujing <admin@leubao.com>
// +----------------------------------------------------------------------
namespace app\common\libs\service;
use think\Session;
use app\common\libs\util\Encrypt;
use app\channel\model\User;
class Partner extends \app\common\libs\service\Service
{
    public $error = '';
	/**
     * 获取当前登录用户资料
     * @return array 
     */
    public function getInfo() {
        if (empty(self::$uInfo)) {
            self::$uInfo = $this->getuInfo($this->isLogin());
        }
        //dump(self::$uInfo);
        return !empty(self::$uInfo) ? self::$uInfo : false;
       
    }
	
    /**
     * 检验用户是否已经登陆
     * @return boolean 失败返回false，成功返回当前登陆用户基本信息
     */
    public function isLogin() {
        $userId = Encrypt::openssl_authcode(Session::get('userId'),'DECODE');
        //查询缓存是否存在 
        $uinfo = json_decode(load_redis('get',Session::get('userId')));
        if (empty($userId) || empty($uinfo)) {
            return false;
        }
        return (int) $userId;
    }

    //登录后台
    public function login($identifier, $password) {
        if (empty($identifier) || empty($password)) {
            $this->error = '用户名密码不能为空';
            return false;
        }
        //验证
        $uInfo = $this->getuInfo($identifier, $password);
        if (false == $uInfo) {
            //记录登录日志
            $this->record($identifier, $password, 0, $this->error);
            $this->error = "用户名或密码错误";
            return false;
        }
        //记录登录日志
        $this->record($identifier, $password, 1);
        //注册登录状态
        $this->registerLogin($uInfo);
        return true;
    }

    /**
     * 检查当前用户是否超级管理员
     * @return boolean
     */
    public function isAdministrator() {
        $uInfo = $this->getInfo();
        if (!empty($uInfo) && $uInfo['role_id'] == self::administratorRoleId) {
            return true;
        }
        return false;
    }
    /**
     * 记录登陆日志
     * @param type $identifier 登陆方式，uid,username
     * @param type $password 密码
     * @param type $status 
     */
    private function record($identifier, $password, $status = 0, $info = '') {
        //登录日志
        $model = new \app\channel\model\Loginlog();
        $model->addLoginLogs(array(
       	 	"is_scene" => '3',
            "username" => $identifier,
            "status" => $status,
            "password" => $status ? '密码保密' : $password,
            "info" => empty($info) ? is_int($identifier) ? '用户ID登录' : '用户名登录' : $info,
        ));
    }
    /**
     * 注册用户登录状态
     * @param array $uInfo 用户信息
     */
    private function registerLogin($uInfo) {
        //写入session
        $session = Encrypt::openssl_authcode((int) $uInfo['id'],'ENCODE');
        Session::set('userId', $session);
        //更新状态
        $model = new User();
        $model->loginStatus((int) $uInfo['id']);
        //缓存配置信息
        load_redis('setex',$session, json_encode($uInfo), 3600);
        //注册权限
        //\Home\Service\RBAC::saveAccessList((int) $uInfo['id']);
    }
    /**
     * 注销登录
     * @Company  承德乐游宝软件开发有限公司
     * @Author   zhoujing      <zhoujing@leubao.com>
     * @DateTime 2017-11-06
     * @param    string        $value                [description]
     * @return   [type]                              [description]
     */
    public function logout()
    {
        //销毁session
        Session::clear();
        //销毁redis
        load_redis('delete',Session::get('userId'));
        return true;
    }
    /**
     * 获取用户信息
     * @param type $identifier 用户名或者用户ID
     * @return boolean|array
     */
    private function getuInfo($identifier, $password = NULL) {
        if (empty($identifier)) {
            return false;
        }
        $model = new User();
        $uInfo = $model->getuInfo($identifier, $password);
        if($uInfo){
            return $uInfo;
        }else{
            $this->error = $model->getError();
            return false;
        }
    }
}