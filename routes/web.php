<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$app->get('/', function () use ($app) {
			
    return $app->version();
});

//统一的注册:

$app->post('FeedBack/apiRegister',['middleware' => 'des','uses'=> 'Api\\Adregister@allRegisterAction']);

//斗鱼注册
$app->get("/Douyu/apiCallBack", "Api\\Douyu@apiCallBackAction");

//今日头条注册

$app->get("/CallBack/apiCallBack","Api\\Today@apiCallBackAction");

//畅思 广告注册

$app->get("/ChangSi/apiCallBack","Api\\ChangSi@apiCallBackAction");

//wead的注册接口

$app->get("/WeAd/apiCallBack","Api\\WeAD@apiCallBackAction");

// 广点通的注册接口
$app->get("Gdt/apiCallBack","Api\\Gdt@apiCallBackAction");  

$app->get("register",function() use($app){
	
	$su = function(){
			for($i=0;$i<10;$i++){
				yield "name".$i;
			}
	};

	//演策
	$timess = function(){
		sleep(rand(1,5));
	};
	
	
	$zhushou = function(){
		while(true){
			$ret = yield ;
			echo $ret;
			sleep(1);
		}
	};
	
	$zhu =  $zhushou();

	for($j=0;$j<10;$j++){
		$zhu->send("fu_".$j);
	}
	
	foreach($su() as $da){
			var_dump($da);
	}
	
		
});








