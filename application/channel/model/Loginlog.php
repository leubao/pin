<?php
// +----------------------------------------------------------------------
// | LubRDF 登录日志
// +----------------------------------------------------------------------
// | Copyright (c) 2017 http://www.leubao.com, All rights reserved.
// +----------------------------------------------------------------------
// | Author: zhoujing <admin@leubao.com>
// +----------------------------------------------------------------------
namespace app\channel\model;

use think\Model;

class Loginlog extends Model
{
    //protected $autoWriteTimestamp = true;
    //protected $createTime = 'logintime';
    protected $auto   = ['logintime'];
    protected $insert = ['loginip'];
    protected function setLoginipAttr()
    {
        return request()->ip();
    }
    protected function setLogintimeAttr()
    {
        return time();
    }
    /**
     * 添加登录日志
     * @param array $data
     * @return boolean
     */
    public function addLoginLogs($data) {
    	//关闭表单验证
		$this->save($data);
        return $this->getData('id') !== false ? true : false;
    }
}