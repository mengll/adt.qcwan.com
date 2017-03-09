<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/2
 * Time: 11:47
 */
namespace App\Model;


use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Jenssegers\Mongodb\MongodbServiceProvider;

class Channel  extends Eloquent{
    protected $collection = 'test_channel';
    const CREATED_AT = null;
    const UPDATED_AT = null;
    public static function getkeyV($channel,$key=0){
        $data = [
            "jrtt"=>[
                "34929" =>"5cff7807-bf93-43ab-bee7-05a4cc783965",
                "1223"  => "05e81aca-b999-4eb4-bc9b-0666d3d47fb5",
                "1127"  => "261045c7-1c1e-47da-9a92-07d3e957b742"
            ],
            "gdt"=>["129"=>[
					"encrypt_key" 	=> "sadsad",
					"sign_key"      =>"asdasdsda"	
					]
			]
        ];
        return isset($data[$channel][$key])?$data[$channel][$key]:0;
    }

}