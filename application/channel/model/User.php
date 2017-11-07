<?php
// +----------------------------------------------------------------------
// | LubRDF 用户模型
// +----------------------------------------------------------------------
// | Copyright (c) 2017 http://www.leubao.com, All rights reserved.
// +----------------------------------------------------------------------
// | Author: zhoujing <admin@leubao.com>
// +----------------------------------------------------------------------
namespace app\channel\model;

use think\Model;
use think\Session;
use app\common\
class User extends Model
{
	public function getuInfo($username = '', $password = '')
	{
		if (empty($identifier)) {
            return false;
        }
        $map = [];
        $map['is_scene'] = 3;
        $map['staus'] = '1';
        $uInfo = $this->where($map)->find();
        if (empty($uInfo)) {
            return false;
        }
        //密码验证
        if (!empty($password) && $this->hashPassword($password, $uInfo['verify']) != $uInfo['password']) {
            return false;
        }
	}
	/**
	 * 用户登录
	 * @Company  承德乐游宝软件开发有限公司
	 * @Author   zhoujing      <zhoujing@leubao.com>
	 * @DateTime 2017-11-06
	 * @param    string        $username             用户名
	 * @param    string        $password             密码
	 * @return   [type]                              [description]
	 */
	public function login($username = '', $password = '')
	{
		//查询用户
		//比对密码
		//缓存用户登录信息
		//返回结果
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
		return true;
	}
	/**
     * 对明文密码，进行加密，返回加密后的密文密码
     * @param string $password 明文密码
     * @param string $verify 认证码
     * @return string 密文密码
     */
    public function hashPassword($password, $verify = "") {
        return md5($password . md5($verify));
    }
    /**
     * 更新登录状态信息
     * @param type $userId
     * @return type
     */
    public function loginStatus($userId) {
        $this->find((int) $userId);
        $this->last_login_time = time();
        $this->last_login_ip = get_client_ip();
        return $this->save();
    }
    /**
     * 插入成功后的回调方法
     * @param type $data 数据
     * @param type $options 表达式
     */
    protected function _after_insert($data, $options) {
        //添加信息后，更新密码字段
        $this->where(array('id' => $data['id']))->save(array(
            'password' => $this->hashPassword($data['password'], $data['verify']),
        ));
    }
}