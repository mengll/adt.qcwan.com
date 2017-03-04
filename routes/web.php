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

//统一的注册

$app->post('FeedBack/apiRegister',['middleware' => 'des','uses'=> 'Api\\Adregister@allRegisterAction']);

//斗鱼注册
$app->get('Douyu/apiCallBack', 'Api\\Douyu@apiCallBackAction');

//今日头条注册

$app->get("CallBack/apiCallBack","Api\\Today@apiCallBackAction");

//wead的注册接口

$app->get("WeAd/apiCallBack","Api\\WeAD@apiCallBackAction");

//畅思 广告注册

$app->get("ChangSi/apiCallBack","Api\\ChangSi@apiCallBackAction");

// 广点通的注册接口




