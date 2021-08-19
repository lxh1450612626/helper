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
function getMonthTime(){
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
function filterEmoji($str){
    $str = preg_replace_callback(
        '/./u',
        function (array $match) {
            return strlen($match[0]) >= 4 ? '' : $match[0];
        },
        $str);

    return $str;
}

//检测socks5代理是否连通
function check_socks5_proxy($ip,$port,$user = '',$pw = ''){
    $targetUrl = "http://baidu.com";
    // 代理服务器
    $proxyServer = $ip.':'.$port;
    // 隧道身份信息
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $targetUrl);
    curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    // 设置代理服务器
    //curl_setopt($ch, CURLOPT_PROXYTYPE, 0); //http
    curl_setopt($ch, CURLOPT_PROXYTYPE, 5); //sock5
    curl_setopt($ch, CURLOPT_PROXY, $proxyServer);
    // 设置隧道验证信息
    curl_setopt($ch, CURLOPT_PROXYAUTH, CURLAUTH_BASIC);

    if($user && $pw){
        curl_setopt($ch,CURLOPT_PROXYUSERPWD,$user.':'.$pw);
    }

    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 2.0.50727;)");
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close($ch);
    if($result){
        return true;
    }
    return false;
}

//ping一个IP地址，能不能通
function ping($ip,$port = 80){
    $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    $time = msectime();
    $res = @socket_connect($socket, $ip, $port);
    $time = msectime() - $time;
    socket_close($socket);
    if($res){
        return ['code'=>200,'time'=>$time];
    }
    return ['code'=>201];
}

function li_md5($pw){
    return md5(sha1($pw).sha1($pw));
}

/**
 * 返回毫秒
 */
function msectime(){
    list($msec, $sec) = explode(' ', microtime());
    $msectime = (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
    return $msectime;
}

/**
 * 时间转换
 */
function time2date($time){
    $str = '';
    if($time >= 24*3600){
        $str = (int) ($time / (24*3600)).'天';
        $time = $time % (24*3600);
    }
    if($time >= 3600){
        $str .= (int) ($time / 3600).'小时';
        $time = $time % 3600;
    }
    if($time >= 60){
        $str .= (int) ($time / 60).'分钟';
        $time = $time % 60;
    }
    if($time){
        $str .= $time.'秒';
    }
    return $str;
}

/**
 * 删除文件
 */
function del_file($dir){
    if(is_dir($dir)){
        $list = scandir($dir);
        foreach ($list as $v){
            if($v != '.' && $v != '..'){
                del_file($dir.'/'.$v);
            }
        }
        //删除文件夹
        rmdir($dir);
    }else{
        if(file_exists($dir)){
            unlink($dir);
        }
    }
}
/**
 * xml转数组
 * @param $xml
 * @return mixed
 */
function xmlToarray($xml){
    $xml_parser = xml_parser_create();
    if(!xml_parse($xml_parser,$xml,true)){
        xml_parser_free($xml_parser);
        return false;
    }else {
        libxml_disable_entity_loader(true);
        return json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
    }
}
//图片转base64
function base64EncodeImage ($image_file) {
    $image_info = getimagesize($image_file);
    $image_data = file_get_contents($image_file);
    $base64_image = 'data:' . $image_info['mime'] . ';base64,' . chunk_split(base64_encode($image_data));
    return $base64_image;
}