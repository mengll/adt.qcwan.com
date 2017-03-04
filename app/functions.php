<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/2
 * Time: 14:58
 */

/*
 * 今日头条生成请求连接地址
 * */

 function jrttCreateUrl($url,$key)
{
    // $d = $mo->getChannel('jrtt',$dat['appid']); //今日头条
    //查询当前的
    $source = "TD";
    $conv_time = time();
    $backurl = $url;
    $url = $backurl."&source={$source}&conv_time={$conv_time}"; //要加密的字符串
    //组合当前的url
    /*********************根据签名原文字符串 $SigTxt，生成签名 Signature******************/
    $Signature = base64_encode(hash_hmac('sha1', $url, $key, true));
    /***************拼接请求串,对于请求参数及签名，需要进行urlencode编码********************/
    $Req = urlencode($Signature);

    return  $url."&signatrue =".$Req;
}


function show(){
    echo "this is show function";
}

