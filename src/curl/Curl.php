<?php

class Curl
{
    /**
     * 以post方式提交数据到对应的接口url
     *
     * @param array $array  需要post的数据数据
     * @param string $url  url
     * @param int $second   url执行超时时间，默认30s
     * @throws WxPayException
     */
    public static function postArrayCurl($array, $url, $second = 30)
    {
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);

        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);//严格校验
        //设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        //post提交方式
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($array,JSON_UNESCAPED_UNICODE));
        //运行curl
        $data = curl_exec($ch);
        //返回结果
        if(!$data){
            $error = curl_errno($ch);
            $res['code'] = 201;
            $res['msg'] = 'curl错误码：'.$error;
        }else{
            $res['code'] = 200;
            $res['data'] = json_decode($data,true);
        }
        curl_close($ch);
        return $res;
    }

    /**
     * 以get方式提交数据到对应的接口url
     * @param string $url  url
     * @param int $second   url执行超时时间，默认30s
     * @throws WxPayException
     */
    public static function getArrayCurl($url, $second = 30)
    {
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);

        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);//严格校验
        //设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        //运行curl
        $data = curl_exec($ch);
        //返回结果
        if(!$data){
            $error = curl_errno($ch);
            $res['code'] = 201;
            $res['msg'] = 'curl错误码：'.$error;
        }else{
            $res['code'] = 200;
            $res['data'] = json_decode($data,true);
        }
        curl_close($ch);
        return $res;
    }

    /**
     * curl保存图片到本地
     * @param $url 地址
     * @param $path 文件保存地址
     * @param $date 是否添加日期文件夹 默认为true
     */
    public static function getImgCurl($url,$path,$date = true){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
        $file = curl_exec($ch);
        curl_close($ch);

        //路径是否添加日期文件夹
        if($date){
            $day = date("Ymd");
            $path = $path.$day.'/';
        }

        //生成文件名
        $file_name = date("YmdHis").rand(1000,9999).'.jpg';
        if(is_dir($path) || mkdir($path,0777,true))
        {
            $file_path = $path.$file_name;
            file_put_contents($file_path,$file);
        }
        return ltrim($file_path,'.');
    }

    /**
     * post方式获取图片到本地
     * @param $url
     * @param $post
     * @param $path 示例'./path/'
     * @param $date 是否添加日期文件夹 默认为true
     * @param int $second
     * @return mixed
     */

    public static function postImgCurl($url,$post,$path,$date = true,$second = 30)
    {
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);//严格校验
        //设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post));
        //运行curl
        $file     =   curl_exec($ch);
        //路径是否添加日期文件夹
        if($date){
            $day = date("Ymd");
            $path = $path.$day.'/';
        }

        //生成文件名
        $file_name = date("YmdHis").rand(1000,9999).'.jpg';
        if(is_dir($path) || mkdir($path,0777,true))
        {
            $file_path = $path.$file_name;
            file_put_contents($file_path,$file);
        }
        return ltrim($file_path,'.');
    }
}