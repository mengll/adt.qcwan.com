<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;
use Mockery\Generator\Parameter;

class Controller extends BaseController
{
    /*
 * 今日头条生成请求连接地址
 * */
    const JRTT    = 1; //今日头条
    const GDT     = 2; //广点通
    const CHANGSI = 3; //畅思
    const WEAD    = 4; //WEAD
    const DOUYU = 5; // 斗鱼


    public static function sendrequest($callback,$ispost=false,$data= array())
    {
        date_default_timezone_set('PRC');
        $curlobj = curl_init();			// 初始化
        curl_setopt($curlobj, CURLOPT_URL, $callback);		// 设置访问网页的URL
        curl_setopt($curlobj, CURLOPT_RETURNTRANSFER, true);			// 执行之后不直接打印出来
        // Cookie相关设置，这部分设置需要在所有会话开始之前设置
        curl_setopt($curlobj, CURLOPT_COOKIESESSION, TRUE);
        curl_setopt($curlobj, CURLOPT_HEADER, 0);
        if($ispost){
            curl_setopt($curlobj, CURLOPT_FOLLOWLOCATION, 1); // 这样能够让cURL支持页面链接跳转
            curl_setopt($curlobj, CURLOPT_POST, 1);
            curl_setopt($curlobj, CURLOPT_POSTFIELDS, $data);
        }

        curl_setopt($curlobj, CURLOPT_HTTPHEADER, array("application/x-www-form-urlencoded; charset=utf-8"));
        $output=curl_exec($curlobj);	// 执行
        curl_close($curlobj);// 关闭cURL
        return $output;
    }


    /*
 * 今日头条生成请求连接地址
 * */

    public function jrttCreateUrl($url,$key)
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

}

