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

class Adtlog  extends Eloquent{
    protected $collection = 'reg_logs';
    const CREATED_AT = null;
    const UPDATED_AT = null;
  
}