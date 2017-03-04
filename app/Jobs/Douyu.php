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

    public function __construct($dat)
    {
        //
        $this->data = $dat;
        echo "run douyu";
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $url = $this->data['backurl'];
        echo "run dou yu";
        switch($this->data['channel']){
            case Adregister::JRTT:
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
            case Adregister::DOUYU:
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
            case Adregister::CHANGSI:

                break;

            case  Adregister::WEAD:

                break;

            case Adregister::GDT:

                break;

        }


    }
}
