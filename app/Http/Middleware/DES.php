<?php

namespace App\Http\Middleware;

use App\Crypt3DES;
use Closure;

class DES
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $key = $request->input("key");
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

        $request->attributes->add(compact('data'));
        return $next($request);
    }
}
