<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/20
 * Time: 19:05
 */
/**
 * 取出随机字符串函数
 * @param $length 取出字符串长度
 * @param $str  指定字符串集合
 * @param $lower 是否转为大写（true 大写  false 小写） 默认小写
 */
function randstr($length,$str='',$lower=false){
    if(empty($str)){
        $str = "abcdefghijklmnopqrstuvwxyz0123456789";
    }
    $result = '';
    for ( $i = 0; $i < $length; $i++ )  {
        $result .= substr($str, mt_rand(0, strlen($str)-1), 1);
    }
    if($lower){
        $result = strtoupper($result);
    }
    return $result;
}

/**
 * 组合多维数组
 */
function getMuchArray($list,$pid = 0,$child = 'child',$parent='nav_parent_id',$id='nav_id'){
    $array = [];
    foreach ($list as $key => $v){
        if($v[$parent] == $pid){
            $v[$child] = getMuchArray($list,$v[$id],$child,$parent,$id);
            $array[] = $v;
        }
    }
    return $array;
}

/**
 * 取本月第一天和最后一天的时间戳
 */
function monthTime()
{
    $date = [];
    $year = date("Y");
    $month = date("m");
    $allday = date("t");
    $date[0] = strtotime($year."-".$month."-1");
    $date[1] = strtotime($year."-".$month."-".$allday);
    return $date;
}

/**
 * 获取当前月份过去月份数组
 */
function getOldMonth($lenth = 1){
    $array = [];
    for ($i=0;$i<$lenth;$i++){
        $month = date('m');
        $year = date('Y');
        $jie_month = $month-$i;
        if($jie_month < 1){
            if($jie_month <= -12){
                $jie_month = abs($jie_month);
                $old_year = floor($jie_month/12);
                $jie_month = $jie_month%12;
                $array[] = ($year-$old_year-1).'-'.(12-$jie_month);
            }else{
                $array[] = ($year-1).'-'.($jie_month+12);
            }
        }else{
            $array[] = $year.'-'.$jie_month;
        }
    }
    return array_reverse($array);
}

/**
 * 过滤微信昵称特殊字符
 * @param $str
 * @return string|string[]|null
 */
function filterEmoji($str)
    {
        $str = preg_replace_callback(
            '/./u',
            function (array $match) {
                return strlen($match[0]) >= 4 ? '' : $match[0];
            },
            $str);

        return $str;
    }