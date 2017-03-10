<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/2
 * Time: 17:21
 */

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Channel;
class Today extends Controller{

    protected $os = array(0=>"android",1=>"ios",2=>"wp",3=>"others"); //设备类型

    public static function jrttCreateUrl($url,$key)
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

    //今日头条的注册

    public function apiCallBackAction(Request $request){

        $type = $request->input('os')?:0;
        $gameid = $request->input("gameid");
        $backurl = $request->input("callback_url");
        $clicktime = $request->input("time")?:time();
        $idfa =  $request->input("idfa");
        $imei = $request->input('imei');
        $productid = $request->input("cid");

        if(!preg_match("/^\d+$/",$gameid)){
            return ["code"=>1,"msg"=>"请填写正确的信息2"];
        }

        if($backurl == ''){
            return ["code"=>1,"msg"=>"请填写正确信息1"];
        }

        if($idfa =='' && $imei ==''){
            return ["code"=>1,"msg"=>"请填写正确的信息3"];
        }

        $muid = isset($imei)?$imei:strtolower(md5($idfa));

        $dat = Channel::where("channel",self::JRTT)->where("mid",crc32($muid))->where("gameid",(int)$gameid)->first();

        if(!empty($dat)){
            return ["code"=>1,"msg"=>"The messages had exists!"];
        }

        $channel = new Channel;
        $channel->type       = $this->os[$type];
        $channel->gameid     = (int) $gameid;
        $channel->muid       = $muid;
        $channel->mid        = crc32($muid);
        $channel->backurl    = $backurl;
        $channel->idfa       = $idfa;
        $channel->imei       = $imei;
        $channel->productid  = $productid;
        $channel->channel    = self::JRTT;
        $channel->click_time = $clicktime;
        $re = $channel->save();

        if($re){
            return ["code"=>0,"msg"=>"成功"];
        }
        return ["code"=>1,"msg"=>"失败"];
    }

}