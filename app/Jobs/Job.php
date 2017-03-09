<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

abstract class Job implements ShouldQueue
{
    /*
    |--------------------------------------------------------------------------
    | Queueable Jobs
    |--------------------------------------------------------------------------
    |
    | This job base class provides a central location to place any logic that
    | is shared across all of your jobs. The trait included with the class
    | provides access to the "queueOn" and "delay" queue helper methods.
    |
    */

    use InteractsWithQueue, Queueable, SerializesModels;

    /*
    * send request
    * */

    public function sendrequest($callback,$ispost=false,$data= array())
    {
        date_default_timezone_set('PRC');
        $curlobj = curl_init();			// 初始化
        curl_setopt($curlobj, CURLOPT_URL, $callback);		// 设置访问网页的URL
        curl_setopt($curlobj, CURLOPT_RETURNTRANSFER, true);			// 执行之后不直接打印出来
        //curl_setopt($curlobj, CURLOPT_HTTPHEADER, array('Expect:'));
        // Cookie相关设置，这部分设置需要在所有会话开始之前设置
       // curl_setopt($curlobj, CURLOPT_COOKIESESSION, TRUE);
        curl_setopt($curlobj, CURLOPT_HEADER, 0);

        //curl_setopt($curlobj, CURLOPT_TIMEOUT_MS, 61000);
        curl_setopt($curlobj, CURLOPT_NOSIGNAL, 1);

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


}
