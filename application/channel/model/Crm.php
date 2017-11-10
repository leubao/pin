<?php
namespace app\channel\model;

use think\Model;

class Crm extends Model
{
	protected function getParamAttr($value)
    {
    	return unserialize($value);
    }
}