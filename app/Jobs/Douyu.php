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

    public function __construct($dat,$callback)
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
                    $back = $this->data['callback'];
                    $dt = $this->sendrequest($back);
                    if($dt){
                        $da = json_decode($dt);
                        if($da['ret'] == 0){
                            $this->data->status =99;
                            $this->data->registe_time = time();
                            $this->data->save();
                        }
                    }

                break;
            case Adregister::DOUYU :
                $dt = \App\Http\Controllers\Api\Douyu::register($url);
                if($dt){
                    $dat = json_decode($dt);
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

                break;

            case Adregister::GDT :
		
			    $dt = $this->sendrequest($this->callback);
				
				if($dt){
					$gdtr = json_decode($dt,true);
					
					if(!$gdtr['ret']){
						$this->data->status = 2;
						$this->data->save();
						echo "保存数据完成";
					}
					
				}
				
                break;
        }

		
    }
}
