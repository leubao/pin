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
use think\Db;
class User extends Model
{
	public function getuInfo($identifier = '', $password = '')
	{
		if (empty($identifier)) {
            return false;
        }
        $uInfo = $this->where('username', $identifier)
        			  ->where('is_scene', 3)
        			  ->where('status', 1)
        			  ->field('id,username,password,verify,role_id,cid,groupid')->relation(['crm_group','crm'])->find();
        if (empty($uInfo)) {
        	$this->error = "账号不存在";
            return false;
        }
        //密码验证->toArray()
        if (!empty($password) && $this->hashPassword($password, $uInfo->verify) != $uInfo->password) {
        	$this->error = "密码错误";
            return false;
        }
        return $uInfo->toArray();
	}
	/**
	 * 会员组信息
	 * @Company  承德乐游宝软件开发有限公司
	 * @Author   zhoujing      <zhoujing@leubao.com>
	 * @DateTime 2017-11-09
	 * @return   [type]        [description]
	 */
	public function crmGroup()
	{
		return $this->belongsTo('CrmGroup','groupid')->field('id,name,price_group,type,settlement');
	}
	/**
	 * 商户信息
	 * @Company  承德乐游宝软件开发有限公司
	 * @Author   zhoujing      <zhoujing@leubao.com>
	 * @DateTime 2017-11-09
	 * @return   [type]        [description]
	 */
	public function crm()
	{
		return $this->belongsTo('Crm','cid')->field('id,name,groupid,cash,level,agent,f_agents,param');
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
		return $this->update([
		    'last_login_time'  => time(),
		    'last_login_ip'    => get_client_ip(),
		],['id'=>(int) $userId]);
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