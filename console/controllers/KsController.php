<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/30 0030
 * Time: 10:27
 */

namespace console\controllers;


use frontend\models\Ahks;
use frontend\models\Bjks;
use frontend\models\Gxks;
use frontend\models\Jsks;
use frontend\models\Lottery;
use yii\web\Controller;

class KsController extends Controller
{

    //解决网页报400错误
    public $enableCsrfValidation = false;

    /**
     * 江苏快三获取接口数据
     */
    public function actionJsks()
    {
        $redis = new \Redis(); //实例化redis
        $redis->connect('127.0.0.1', '6379'); //建立redis服务连接
        $url=file_get_contents("http://e.apiplus.net/newly.do?token=t64fb3909e113c8d2k&code=jsk3&format=json");
        $api = json_decode($url, true);
        //拿到期号到数据库里面去查，看有没有这个期号
        $ssctxffc = Jsks::findOne(['period'=>$api['data'][0]['expect']]);
        if (!$ssctxffc){//期号存在
            $date = date('H:i:s');
            $redis->set('jsks',$date);
            $redis->expire('jsks',600);
            $ssctxffc = new Jsks();
            $ssctxffc->time = time();
            $ssctxffc->name = '江苏快三';
            $ssctxffc->number = $api['data'][0]['opencode'];
            $ssctxffc->receive_time = strtotime($api['data'][0]['opentime']);
            $ssctxffc->period = $api['data'][0]['expect'];
            $ssctxffc->status = 1;
                  $ssctxffc->color_id = 23;
            $behind_period = substr($api['data'][0]['expect'],-3);
            if($behind_period == '082'){
                $time = date('Ymd',time());
                $ssctxffc->behind_period  = $time.'001';
                $ssctxffc->save();//保存
            }else{
                $ssctxffc->behind_period = $ssctxffc->period+1;//拿到前端显示下一期的开奖期号
                $ssctxffc->save();//保存
            }
            $lottery = new Lottery();
            $lottery->Winning(23);
        }
    }




    /**
     * 安徽快三获取接口数据
     */
    public function actionAhks()
    {
        $redis = new \Redis(); //实例化redis
        $redis->connect('127.0.0.1', '6379'); //建立redis服务连接
        $url=file_get_contents("http://e.apiplus.net/newly.do?token=t64fb3909e113c8d2k&code=gd11x5&format=json");
        $api = json_decode($url, true);
        //拿到期号到数据库里面去查，看有没有这个期号
        $ssctxffc = Ahks::findOne(['period'=>$api['data'][0]['expect']]);
        if (!$ssctxffc){//期号存在
            $date = date('H:i:s');
            $redis->set('ahks',$date);
            $redis->expire('ahks',600);
            $ssctxffc = new Ahks();
            $ssctxffc->time = time();
            $ssctxffc->name = '安徽快三';
            $ssctxffc->number = $api['data'][0]['opencode'];
            $ssctxffc->receive_time = strtotime($api['data'][0]['opentime']);
            $ssctxffc->period = $api['data'][0]['expect'];
            $ssctxffc->status = 1;
                 $ssctxffc->color_id = 24;
            $behind_period = substr($api['data'][0]['expect'],-3);
            if($behind_period == '080'){
                $time = date('Ymd',time());
                $ssctxffc->behind_period  = $time.'001';
                $ssctxffc->save();//保存
            }else{
                $ssctxffc->behind_period = $ssctxffc->period+1;//拿到前端显示下一期的开奖期号
                $ssctxffc->save();//保存
            }
            $lottery = new Lottery();
            $lottery->Winning(24);
        }
    }



    /**
     * 北京快三获取接口数据
     */
    public function actionBjks()
    {
        $redis = new \Redis(); //实例化redis
        $redis->connect('127.0.0.1', '6379'); //建立redis服务连接
        $url=file_get_contents("http://e.apiplus.net/newly.do?token=t64fb3909e113c8d2k&code=bjk3&format=json");
        $api = json_decode($url, true);
        //拿到期号到数据库里面去查，看有没有这个期号
        $ssctxffc = Bjks::findOne(['period'=>$api['data'][0]['expect']]);
        if (!$ssctxffc){//期号存在
            $date = date('H:i:s');
            $redis->set('bjks',$date);
            $redis->expire('bjks',600);
            $ssctxffc = new Bjks();
            $ssctxffc->time = time();
            $ssctxffc->name = '北京快三';
            $ssctxffc->number = $api['data'][0]['opencode'];
            $ssctxffc->receive_time = strtotime($api['data'][0]['opentime']);
            $ssctxffc->period = $api['data'][0]['expect'];
            $ssctxffc->status = 1;
                 $ssctxffc->color_id = 37;
//            $behind_period = substr($api['data'][0]['expect'],-2);
//            if($behind_period == '89'){
//                $time = date('Ymd',time());
//                $ssctxffc->behind_period  = $time.'01';
//                $ssctxffc->save();//保存
//            }else{
                $ssctxffc->behind_period = $ssctxffc->period+1;//拿到前端显示下一期的开奖期号
                $ssctxffc->save();//保存
//            }
            $lottery = new Lottery();
            $lottery->Winning(37);
        }
    }



    /**
     * 广西快三获取接口数据
     */
    public function actionGxks()
    {
        $redis = new \Redis(); //实例化redis
        $redis->connect('127.0.0.1', '6379'); //建立redis服务连接
        $url=file_get_contents("http://e.apiplus.net/newly.do?token=t64fb3909e113c8d2k&code=gxk3&format=json");
        $api = json_decode($url, true);
        //拿到期号到数据库里面去查，看有没有这个期号
        $ssctxffc = Gxks::findOne(['period'=>$api['data'][0]['expect']]);
        if (!$ssctxffc){//期号存在
            $date = date('H:i:s');
            $redis->set('gxks',$date);
            $redis->expire('gxks',600);
            $ssctxffc = new Gxks();
            $ssctxffc->time = time();
            $ssctxffc->name = '广西快三';
            $ssctxffc->number = $api['data'][0]['opencode'];
            $ssctxffc->receive_time = strtotime($api['data'][0]['opentime']);
            $ssctxffc->period = $api['data'][0]['expect'];
            $ssctxffc->status = 1;
                        $ssctxffc->color_id = 38;
            $behind_period = substr($api['data'][0]['expect'],-3);
            if($behind_period == '078'){
                $time = date('Ymd',time());
                $ssctxffc->behind_period  = $time.'001';
                $ssctxffc->save();//保存
            }else{
                $ssctxffc->behind_period = $ssctxffc->period+1;//拿到前端显示下一期的开奖期号
                $ssctxffc->save();//保存
            }
            $lottery = new Lottery();
            $lottery->Winning(38);
        }
    }


}