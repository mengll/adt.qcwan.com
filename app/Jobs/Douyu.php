<?php
namespace App\Jobs;

use App\Http\Controllers\Api\Adregister;
use App\Model\Channel;

class Douyu extends Job
{
    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $data;
	protected $callback;

    public function __construct($dat,$callback='')
    {
        $this->data = $dat;
		$this->callback = $callback;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
	
        //$url = $this->data->backurl;
		//添加注册的请求的日志的处理，将包含数据的请求的时间的处理
        switch($this->data->channel){
            case Adregister::JRTT :
                  	$dt = $this->sendrequest($this->callback);
                    if($dt){
                        $da = json_decode($dt,true);
                        if($da['ret'] == 0){
                            $this->data->status =2;
                            $this->data->registe_time = time();
                            $this->data->save();
							echo "今日头条完成";
                        }
                    }

                break;
            case Adregister::DOUYU :
                $dt = \App\Http\Controllers\Api\Douyu::register($this->data->backurl);
                if($dt){
                    $dat = json_decode($dt,true);
                    if($dat['error'] == 0){
                        $this->data->status =Adregister::ACTIVE;
                        $this->data->registe_time = time();
                        $this->data->save();
                    }
                }
                break;
            case Adregister::CHANGSI :

                break;

            case  Adregister::WEAD :
					$url = $this->data->callback;
					if(isset($this->data->imei) && $this->data->imei !=''){
						$url += '&imei='.$this->data->imei;
					}else{
						$url += '&idfa='.$this->data->idfa;
					}
					
				$dt = $this->sendrequest(urlencode($url));
				if($dt){
					$this->data->status = 2;
					$this->data->save();
					$this->data->registe_time = time();
					echo "wead的编码的规范";
				}
					
                break;

            case Adregister::GDT :
		
			    $dt = $this->sendrequest($this->callback);
				if($dt){
					$gdtr = json_decode($dt,true);
					
					if(!$gdtr['ret']){
						$this->data->status = 2;
						$this->data->save();
						$this->data->registe_time = time();
						echo "保存数据完成";
					}
				}
				
                break;
        }

    }
}
