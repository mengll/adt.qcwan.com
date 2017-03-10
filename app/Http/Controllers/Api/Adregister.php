<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/2
 * Time: 9:42
 */
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Channel;
use Illuminate\Support\Facades\Event;

class  Adregister extends Controller{

    public $tableName = "test_channel";
    const JRTT = 1; // 今日头条
    const DOUYU = 5;//斗鱼平台
    const GDT = 2; //广点通
    const CHANGSI= 3; //畅思
    const WEAD = 4; //WEAD
    const STATUS_SUCCESS = 12; //已经注册成功！

    public function allRegisterAction(Request $request){
        //echo "this is Allregister api";
        $dat   	= $request->attributes->get("data");
        $gameid = isset($dat['pid'])?$dat['pid']:0;
        $imei   = isset($dat['imei'])?$dat['imei']:'';
        $rid    = isset($dat['rid']) ?$dat['rid']:0;
		$idfa   = isset($dat['idfa'])?$dat['rid']:'';
        $register_time = isset($dat['time'])?$dat['time']:date("Y-m-d H:i:s");
		
		if(!$gameid) return ["code"=>1,"msg"=>"please add the param gameid"];

        $mid = isset($imei)?strtolower(md5($imei)):strtolower(md5(urldecode($dat['idfa']))); //接收到的设备码
		$mid = $dat['muid'];
        $data = Channel::where("muid",$mid)->where("gameid",(int)$gameid)->first();

		if(!$data){
		   return ["code"=>2,"msg"=>"您好没有相关的设备信息!no messages!"];
		}
		
	
        if($data->status == self::STATUS_SUCCESS) return ['code'=>1,"msg"=>"您好,你的账户信息已经被记录 had exists!"];
			
        //跳转到不同的分支
        switch($data->channel){
            case self::JRTT:
                    $key = Channel::getkeyV('jrtt',$gameid);
                    $url = $this->jrttCreateUrl($data->backurl,$key);
                    dispatch(new \App\Jobs\Douyu($data,$url)); //跑出当前处理
                    return ["code"=>1,"msg"=>"今日头条","url"=>$url];
                break;
            case self::DOUYU:
                    dispatch(new \App\Jobs\Douyu($data));
                break;
				
			case self::GDT:
					//生成广点通的连接地址的处理	
					$url  = Gdt::gdturl($data);
					if($url)
					{
						dispatch(new \App\Jobs\Douyu($data,$url)); //发送当前处理
					}
				break;
			case self::WEAD:
				    dispatch(new \App\Jobs\Douyu($data));
				break;
			
			//WEAD 广告平台
		
        }
        // 查询当前的用户是否存在，如果存在直接返回

        //return $request->attributes->get("data");
    }
}

