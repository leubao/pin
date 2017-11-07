<?php
// +----------------------------------------------------------------------
// | LubRDF 用户登录控制器
// +----------------------------------------------------------------------
// | Copyright (c) 2017 http://www.leubao.com, All rights reserved.
// +----------------------------------------------------------------------
// | Author: zhoujing <admin@leubao.com>
// +----------------------------------------------------------------------
namespace app\common\libs\service;
use think\Session;
use app\common\libs\util\Encrypt;
class Partner
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
        if (empty($userId)) {
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
            //$this->record($identifier, $password, 0);
            
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
     * 注销登录状态
     * @return boolean
     */
    public function logout() {
       // session('[destroy]');
      //  return true;
    }

    /**
     * 记录登陆日志
     * @param type $identifier 登陆方式，uid,username
     * @param type $password 密码
     * @param type $status 
     */
    private function record($identifier, $password, $status = 0) {
        //登录日志
        model('Channel/Loginlog')->addLoginLogs(array(
       	 	"is_scene" => '3',
            "username" => $identifier,
            "status" => $status,
            "password" => $status ? '密码保密' : $password,
            "info" => is_int($identifier) ? '用户ID登录' : '用户名登录',
        ));
    }
    /**
     * 注册用户登录状态
     * @param array $uInfo 用户信息
     */
    private function registerLogin(array $uInfo) {
        //写入session
        session(self::UidKey, \Libs\Util\Encrypt::authcode((int) $uInfo['id'], ''));
        Session::set('userId', Encrypt::openssl_authcode((int) $uInfo['id']));
        //更新状态
        //D('Home/User')->loginStatus((int) $uInfo['id']);
        //注册权限
        //\Home\Service\RBAC::saveAccessList((int) $uInfo['id']);
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
        return model('Channel/User')->getuInfo($identifier, $password);
    }
}