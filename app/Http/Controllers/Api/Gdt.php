<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/2
 * Time: 16:19
 */
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Model\Channel;

use Illuminate\Http\Request;

class Gdt extends Controller{

    public function apiCallBackAction(Request $request){
		
        $app_type = strtolower( $request->input('app_type'));
        $appid = $request->input("appid");
        $click_id = $request->input("click_id");
        $click_time = $request->input("click_time")?:time();
        $muid =  $request->input("muid");
        $gameid = $request->input("gameid");
    
        if(!preg_match("/^\d+$/",$gameid)){
            return ["code"=>1,"msg"=>"请填写正确的信息2"];
        }


        if($muid ==''){
            return ["code"=>1,"msg"=>"请填写正确的信息3"];
        }

        $dat = Channel::where("channel",self::DOUYU)->where("muid",$muid)->where("gameid",(int)$gameid)->first();

        if(!empty($dat)){

            return ["code"=>1,"msg"=>"The messages had exists!"];
        }

        $channel = new Channel;
        $channel->type 			= $app_type;
        $channel->gameid 		= (int) $gameid;
        $channel->muid 			= $muid;
		$channel->click_time 	= $click_time;
		$channel->click_id 		= $click_id;
        $channel->channel 		= self::Gdt;
        $re 					= $channel->save();

        if($re){
            return ["code"=>0,"msg"=>"成功"];
        }
        return ["code"=>1,"msg"=>"失败"];
    }

    public static function register($url){
        return static::sendrequest($url,false,[]);
    }
	
	
    /**
     * @param  [type]     $app_id      [description]  android 应用为开放平台移动应用的 id,或者 ios 应用在 Apple App Store 的 id
     * @param  [type]     $encrypt_key [description]  加密密钥 encrypt_key
     * @param  [type]     $sign_key    [description]  签名密钥 sign_key
     * @param  [type]     $uid         [description]  广告主 ID(必选)
     * @param  [type]     $conv_type   [description]  转化类型(必选)
     * @param  [type]     $app_type    [description]  转化应用类型(必选) ios 应用取值为 IOS,Android 应用取值为 ANDROID
     * @param  [type]     $click_id    [description]  广点通后台生成的点击 id,广点通系统中标识用户每次点击生成的唯一标识
     * @param  [type]     $muid        [description]  用户设备的 IMEI 或 idfa 进行 MD5SUM 以后得到的 32 位全小写 MD5 表现字符串
     * @return [type]                  [description]
     */
    private function createUrl($app_id, $encrypt_key, $sign_key, $uid, $conv_type, $app_type, $click_id, $muid)
    {
        $conv_time    = time();
        $url          = 'http://t.gdt.qq.com/conv/app/' . $app_id . '/conv?';
        //参数拼接
        $query_string = "click_id=" . urlencode($click_id) . "&muid=" . urlencode($muid) . "&conv_time=" . urlencode($conv_time);
        //urlencode转化
        $encode_page  = urlencode($url . $query_string);
        //property
        $property     = $sign_key . '&GET&' . $encode_page;
        //md5加密
        $signature    = strtolower(md5($property));
        //base_data
        $base_data    = $query_string . "&sign=" . urlencode($signature);
        //通过base_data和enctype_type进行异或
        $data         = urlencode($this->SimpleXor($base_data, $encrypt_key));
        //组装
        $attachment   = "conv_type=" . urlencode($conv_type) . '&app_type=' . urlencode($app_type) . '&advertiser_id=' . urlencode($uid);
        //最终的拼接
        $lastUrl      = $url . "v=" . $data . "&" . $attachment;
        return $lastUrl;
    }

    /**
     * 简单异或
     * @param  [type]     $data [description]
     * @param  [type]     $key  [description]
     * @return [type]           [description]
     */
    private function SimpleXor($data,$key)
    {
        $str   = '';
        $len   = strlen($data);
        $len2  = strlen($key);
        for($i = 0; $i < $len; $i ++){
            $j = $i % $len2;
            $str .= ($data[$i]) ^ ($key[$j]);
        }
        return base64_encode($str);
    }
	
	
	


}