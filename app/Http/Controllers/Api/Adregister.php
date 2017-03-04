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
    const ACTIVE = 2; //已经注册
    const CHANGSI= 3; //畅思
    const WEAD = 4; //WEAD
    const STATUS_SUCCESS = 12; //已经注册成功！

    public function allRegisterAction(Request $request){
        //echo "this is Allregister api";
        $dat  = $request->attributes->get("data");
        $gameid = $dat['pid'];
        $imei = $dat['imei'];
        $rid  = $dat['rid'];
        $register_time = $dat['time'];

        $mid = isset($imei)?strtolower(md5($imei)):strtolower(md5(urldecode($dat['idfa']))); //接收到的设备码

        $data = Channel::where("muid",$mid)->where("gameid",(int)$gameid)->get();

        $dt = $data ->toArray()[0];

        if(!count($data->toArray())) {
            return ["code"=>1,"msg"=>"您好没有相关的设备信息!no messages!"];
        }

        if($dt['status'] == self::STATUS_SUCCESS) return ['code'=>1,"msg"=>"您好,你的账户信息已经被记录 had exists!"];

        //跳转到不同的分支
        switch($dt['channel']){
            case self::JRTT:
                    $key = Channel::getkeyV('jrtt',$gameid);
                    $url = $this->jrttCreateUrl($dt['backurl'],$key);
                    $dt['callback'] = $url;
                    dispatch(new \App\Jobs\Douyu($dt)); //跑出当前处理
                    return ["code"=>1,"msg"=>"今日头条","url"=>$url];
                break;
            case self::DOUYU:
                    dispatch($data);
                break;

        }


        // 查询当前的用户是否存在，如果存在直接返回

        return $request->attributes->get("data");
    }
}

