<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/2
 * Time: 17:58
 */
namespace  App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Jobs\Douyu;
use App\Model\Channel;
use Illuminate\Http\Request;

class ChangSi extends Controller{
    protected $os = array(0=>"android",1=>"ios",2=>"wp",3=>"others"); //mobile type

    public function apiCallBackAction(Request $request){
		//优化 ￥request->all()  foreach() 的方式便利保存

        $type 		= strtolower($request->input('os'));
        $gameid 	= $request->input("gameid");
        $backurl 	= $request->input("callback");
        $clicktime  = $request->input("time")?:time();

        $idfa 		=  $request->input("idfa");
        $imei 		= $request->input('imei');
		$ip 		= $request->input('ip');
		$clickid 	= $request->input('clickid');
		$mac		= $request->input('mac');

        if(!preg_match("/^\d+$/",$gameid)){
            return ["code"=>1,"msg"=>"gameid is null!"];
        }

        if($backurl == ''){
            return ["code"=>1,"msg"=>"backURL is null please input the value!"];
        }

        if($idfa =='' && $imei ==''){
            return ["code"=>1,"msg"=>"hi !you loss the imei or idfa"];
        }
		
		if($mac ==''){
			return ['code'=>1,"msg"=>"not exists MAC"];
		}

        $muid = isset($imei)?strtolower($imei):strtolower(md5($idfa));

        $dat = Channel::where("channel",self::CHANGSI)->where("muid",$muid)->where("gameid",(int)$gameid)->first();

        if(!empty($dat)){
            return ["code"=>1,"msg"=>"The messages had exists!"];
        }

        $channel = new Channel;
        $channel->type 		= $this->os[$type];
        $channel->gameid 	= (int) $gameid;
        $channel->muid 		= $muid;
        $channel->mid 		= crc32($muid);
		$channel->ip   		= $ip;	
        $channel->idfa 		= $idfa;
        $channel->imei 		= $imei;
        $channel->backurl 	= $backurl;
        $channel->clicktime = $clicktime;
        $channel->channel 	= self::CHANGSI;
		$channel->mac 		= $mac;
        $re = $channel->save();

        if($re){
            return ["code"=>0,"msg"=>"sucess"];
        }
        return ["code"=>1,"msg"=>"失败"];
    }



}
