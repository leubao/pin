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
	//array(填充字段,填充内容,[填充条件,附加规则])
    protected $_auto = array(
        array('logintime', 'time', 1, 'function'),
        array('loginip', 'get_client_ip', 3, 'function'),
    );
    /**
     * 添加登录日志
     * @param array $data
     * @return boolean
     */
    public function addLoginLogs($data) {
    	//关闭表单验证
		$this->create($data);
        return $this->add() !== false ? true : false;
    }
}