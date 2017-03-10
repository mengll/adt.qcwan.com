<?php

namespace App\Http\Middleware;

use App\Crypt3DES;
use Closure;
use Illuminate\Http\Request;
class DES
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle( Request $request, Closure $next)
    {
	
        $key = $request->input("key");
		if($key =='') return ["code"=>1,"msg"=>"not exists key!"];
        $des = "f69fc0e2575511a7f69fc0e2";
        $dat = Crypt3DES::decrypt($key,$des);
        $dt = explode('&',$dat);
        $data = array();
        foreach($dt as $key =>$v){
            if($v){
                $b = explode("=",$v);
                $data[$b[0]] = $b[1];
            }
        }
	
		$data = ["muid"=>"23fd7bcebcc040238653950416e319b5",'pid'=>121]; //测试数据写在此地
        $request->attributes->add(compact('data'));

        return $next($request);
    }
}
