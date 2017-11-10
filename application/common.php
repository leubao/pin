<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
/**
 * 获取客户端IP地址
 * @param integer $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
 * @param boolean $adv 是否进行高级模式获取（有可能被伪装）
 * @return mixed
 */
function get_client_ip($type = 0, $adv = false) {
    $type = $type ? 1 : 0;
    static $ip = NULL;
    if ($ip !== NULL)
        return $ip[$type];
    if ($adv) {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $pos = array_search('unknown', $arr);
            if (false !== $pos)
                unset($arr[$pos]);
            $ip = trim($arr[0]);
        }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
    } elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    // IP地址合法验证
    $long = sprintf("%u", ip2long($ip));
    $ip = $long ? array($ip, $long) : array('0.0.0.0', 0);
    return $ip[$type];
}
/**
 * 系统通用生成订单号
 * @param  string $uuid [description]
 * @return [type]       [description]
 */
function get_order_sn($uuid = '')
{ 
    return substr(date('Ymd'),3).str_pad($uuid,4,mt_rand(1, 999999), STR_PAD_LEFT). str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
}
/**
 * 产生一个指定长度的随机字符串,并返回给用户 
 * @param type $len 产生字符串的长度
 * @param $type string  生成类型 int 纯数字   string  字符串
 * @return string 随机字符串
 */
function genRandomString($len = 6,$type = null) {
    if($type == 'int'){
        $chars = array("0", "1", "2","3", "4", "5", "6", "7", "8", "9");
    }else{
        $chars = array(
            "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",
            "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",
            "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G",
            "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R",
            "S", "T", "U", "V", "W", "X", "Y", "Z", "0", "1", "2",
            "3", "4", "5", "6", "7", "8", "9"
        );
    }  
    $charsLen = count($chars) - 1;
    // 将数组打乱 
    shuffle($chars);
    $output = "";
    for ($i = 0; $i < $len; $i++) {
        $output .= $chars[mt_rand(0, $charsLen)];
    }
    return $output;
}

/**
 * 数据脱敏处理
 * @param  array    $data  待处理数据
 * @param  array $field 脱敏字段
 * @return array
 * Author IT
 */
function desensitization($data,$field){
    if(empty($data) || empty($field)){
        return false;
    }else{
        foreach ($a1 as $key => $value) {
            if(in_array($key,$a2)){
                unset($a1[$key]);
            }
        }
    }
    return $data;
}
/**
 * @Author   zhoujing   <zhoujing@leubao.com>
 * @DateTime 2017-09-02
 * @param    string     $begin_time           开始时间
 * @param    string     $end_time             结束时间
 * @param    string     $type                 类型 unix 时间戳 date 格式时间
 * @return   array
 */
function timediff($begin_time,$end_time,$type = 'unix') {
    $begin_time = $type == 'unix' ? $begin_time : strtotime($begin_time);
    $end_time = $type == 'unix' ? $end_time : strtotime($end_time);  
    if($begin_time < $end_time){ 
        $starttime = $begin_time; 
        $endtime = $end_time; 
    }else{ 
        $starttime = $end_time; 
        $endtime = $begin_time; 
    } 
    $timediff = $endtime - $starttime;
    $days = intval($timediff/86400); 
    $remain = $timediff%86400; 
    $hours = intval($remain/3600); 
    $remain = $remain%3600; 
    $mins = intval($remain/60); 
    $secs = $remain%60; 
    $res = ["day" => $days,"hour" => $hours,"min" => $mins,"sec" => $secs]; 
    return $res; 
}
/*身份证号码校验
*@param $idcard 身份证号码验证
*/
function checkIdCard($idcard){
    // 只能是18位
    if(strlen($idcard)!=18){
        return false;
    }
    // 取出本体码
    $idcard_base = substr($idcard, 0, 17);
    // 取出校验码
    $verify_code = substr($idcard, 17, 1);
    // 加权因子
    $factor = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
    // 校验码对应值
    $verify_code_list = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
    // 根据前17位计算校验码
    $total = 0;
    for($i=0; $i<17; $i++){
        $total += substr($idcard_base, $i, 1)*$factor[$i];
    }
    // 取模
    $mod = $total % 11;
    // 比较校验码
    if($verify_code == $verify_code_list[$mod]){
        return true;
    }else{
        return false;
    }
}
/**
 * 格式化金额
 *
 * @param int $money
 * @param int $len
 * @param string $sign
 * @return string
 */
function format_money($money, $len=2, $sign='￥'){
    $negative = $money >= 0 ? '' : '-';
    $int_money = intval(abs($money));
    $len = intval(abs($len));
    $decimal = '';//小数
    if ($len > 0) {
        $decimal = '.'.substr(sprintf('%01.'.$len.'f', $money),-$len);
    }
    $tmp_money = strrev($int_money);
    $strlen = strlen($tmp_money);
    for ($i = 3; $i < $strlen; $i += 3) {
        $format_money .= substr($tmp_money,0,3).',';
        $tmp_money = substr($tmp_money,3);
    }
    $format_money .= $tmp_money;
    $format_money = strrev($format_money);
    return $sign.$negative.$format_money.$decimal;
}
/**
*数字金额转换成中文大写金额的函数
*String Int  $num  要转换的小写数字或小写字符串
*return 大写字母
*小数位为两位
**/
function num_to_rmb($num){
    $c1 = "零壹贰叁肆伍陆柒捌玖";
    $c2 = "分角元拾佰仟万拾佰仟亿";
    //精确到分后面就不要了，所以只留两个小数位
    $num = round($num, 2); 
    //将数字转化为整数
    $num = $num * 100;
    if (strlen($num) > 10) {
            return "金额太大，请检查";
    } 
    $i = 0;
    $c = "";
    while (1) {
        if ($i == 0) {
            //获取最后一位数字
            $n = substr($num, strlen($num)-1, 1);
        } else {
            $n = $num % 10;
        }
        //每次将最后一位数字转化为中文
        $p1 = substr($c1, 3 * $n, 3);
        $p2 = substr($c2, 3 * $i, 3);
        if ($n != '0' || ($n == '0' && ($p2 == '亿' || $p2 == '万' || $p2 == '元'))) {
                $c = $p1 . $p2 . $c;
        } else {
                $c = $p1 . $c;
        }
        $i = $i + 1;
        //去掉数字最后一位了
        $num = $num / 10;
        $num = (int)$num;
        //结束循环
        if ($num == 0) {
                break;
        } 
    }
    $j = 0;
    $slen = strlen($c);
    while ($j < $slen) {
        //utf8一个汉字相当3个字符
        $m = substr($c, $j, 6);
        //处理数字中很多0的情况,每次循环去掉一个汉字“零”
        if ($m == '零元' || $m == '零万' || $m == '零亿' || $m == '零零') {
                $left = substr($c, 0, $j);
                $right = substr($c, $j + 3);
                $c = $left . $right;
                $j = $j-3;
                $slen = $slen-3;
        } 
        $j = $j + 3;
    } 
    //这个是为了去掉类似23.0中最后一个“零”字
    if (substr($c, strlen($c)-3, 3) == '零') {
            $c = substr($c, 0, strlen($c)-3);
    }
    //将处理的汉字加上“整”
    if (empty($c)) {
        return "零元整";
    }else{
        return $c . "整";
    }
}
/**
 * lubTicket redis 操作API
 * @param  string $apiport 要操作的接口
 * @param  string $key     键名
 * @param  string $value   键值
 * @param  string $time    有效时间
 * @return true|false
 */
function load_redis($apiport, $key, $value = '', $time = ''){
    if (!extension_loaded('redis')) {
        throw new \BadFunctionCallException('not support: redis');
    }
    $redis = new \Redis();
    $redis->connect(config('database.redis_host'),config('database.redis_port'));
    $redis->auth(config('database.redis_auth'));
    $redis->select(config('database.redis_database'));
    switch ($apiport) {
        case 'lsize':
            //判断列表中元素个数
            $return = $redis->lsize($key);
            break;
        case 'rPop':
            //获取队列中最后一个元素，且移除
            if((int)$redis->lsize($key) > 0){
                $return = $redis->rPop($key);
            }else{
                $return = false;
            }
            break;
        case 'lpush':
            //写入带处理队列，若存在则不再写入
            $return = $redis->lPush($key,$value);
            break;
        case 'set':
            $return = $redis->set($key,$value);
            break;
        case 'setex':
            /**
             * 设置有效期
             */
            $return = $redis->setex($key, $time, $value);
            break;
        case 'expire':
            /*更新key的有效期*/
            $return = $redis->expire($key,$value);
            break;
        case 'get':
            $return = $redis->get($key);
            break;
        case 'lrange':
            //返回list 中的元素 返回名称为key的list中start至end之间的元素（end为 -1 ，返回所有） value 为开始位置 $time 为结束位置
            $return = $redis->lrange($key,$value,$time);
            break;
        case 'incrby':
            //计数器+
            $return = $redis->incrBy($key,$value);
            break;
        case 'decrby':
            //计数器- 返回操作后的值
            $return = $redis->decrBy($key,$value);
            break;
        case 'delete':
            //删除指定key
            $return = $redis->delete($key);
            break;
        default:
            $return = '';
            break;

    }
    return $return;
}