<?php
namespace app\channel\model;

use think\Model;

class CrmRecharge extends Model
{

	public function deduct()
	{
		//获取扣款条件 最终订单显示实际售出金额
		
		//当前渠道商、实际金额
		//多级扣款情况 扣款条件+扣款金额 cid => '1','money' => '222', 
		//逐级扣款从级别最高开始扣款
	}
	public function money_map($cid = '', $money = '')
	{
		//多级渠道商
		//是否有返利
	}
	//计算各级结算金额
	public function level_price($value='')
	{
		
	}
	/**
     * 金额扣除条件
     * @param $param 渠道商ID
     * @param $type 2公司客户8全员销售
     * @param $channel_id int 渠道商
     */
    function money_map($param,$type = '1'){
        if(!empty($param)){
            if($type == '1'){
                $param = D('Crm')->where(array('id'=>$param))->field('id,level,f_agents')->find();
                /*
                 * 读取上级渠道商的ID
                 */
                $Config = cache("Config");
                switch ($param['level']){
                    case $Config['level_1'] :
                        //一级渠道商
                        return $param['id'];
                        break;
                    case $Config['level_2'] :
                        //二级级渠道商
                        return $param['f_agents'];
                        break;
                    case $Config['level_3'] :
                        //三级渠道商  获取二级的上一级ID  
                        $cid = Libs\Service\Operate::do_read('Crm',0,array('id'=>$param['f_agents']),'',array('f_agents'));
                        return $cid['f_agents'];
                        break;
                }
            }
            if($type == '4'){
                $db = D('User');$field = 'id';
                $param = D('User')->where(array('id'=>$param))->field('id')->find();
                return $param['id'];
            }
        }else{
            return false;
        }        
    }
}