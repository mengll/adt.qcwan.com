<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/2
 * Time: 18:25
 */

namespace App\Http\Controllers\Api;
use APP\Http\Controllers\Controller;
use App\Model\Channel;
use Illuminate\Http\Request;

class Gdt extends Controller{

    //注册接口
    protected $os = array(0=>"android",1=>"ios",2=>"wp",3=>"others"); //设备类型

    public function apiCallBackAction(Request $request){
        $type = strtolower( $request->input('os'));
        $gameid = $request->input("appid");
        $backurl = $request->input("callback_url");
        $clicktime = $request->input("time")?:time();
        $idfa =  $request->input("idfa");
        $ip = $request->input("ip");
        $osversion = $request->input("osversion");
        $imei = $request->input('imei');

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

        $dat = Channel::where("channel",self::DOUYU)->where("mid",crc32($muid))->where("gameid",(int)$gameid)->first();

        if(!empty($dat)){
            return ["code"=>1,"msg"=>"The messages had exists!"];
        }

        $channel = new Channel;
        $channel->type = $type;
        $channel->gameid = (int) $gameid;
        $channel->muid = $muid;
        $channel->mid = crc32($muid);
        $channel->ip = $ip;
        $channel->backurl = $backurl;
        $channel->click_time = $clicktime;
        $channel->osversion = $osversion;
        $channel->channel = self::DOUYU;
        $re = $channel->save();

        if($re){
            return ["code"=>0,"msg"=>"成功"];
        }
        return ["code"=>1,"msg"=>"失败"];
    }

}