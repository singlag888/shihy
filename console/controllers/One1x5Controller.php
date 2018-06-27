<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/30 0030
 * Time: 10:27
 */

namespace console\controllers;


use frontend\models\Gd11x5;
use frontend\models\Js11x5;
use frontend\models\Jx11x5;
use frontend\models\Lottery;
use frontend\models\Sh11x5;
use yii\web\Controller;

class One1x5Controller extends Controller
{

    //解决网页报400错误
    public $enableCsrfValidation = false;

    /**
     * 广东11选5获取接口数据
     */
    public function actionGd11x5()
    {
        $redis = new \Redis(); //实例化redis
        $redis->connect('127.0.0.1', '6379'); //建立redis服务连接
        $url=file_get_contents("http://e.apiplus.net/newly.do?token=t64fb3909e113c8d2k&code=gd11x5&format=json");
        $api = json_decode($url, true);
        //拿到期号到数据库里面去查，看有没有这个期号
        $ssctxffc = Gd11x5::findOne(['period'=>$api['data'][0]['expect']]);
        if (!$ssctxffc){//期号存在
            $date = date('H:i:s');
            $redis->set('gd11x5',$date);
            $redis->expire('gd11x5',600);
            $ssctxffc = new Gd11x5();
            $ssctxffc->time = time();
            $ssctxffc->name = '广东11选5';
            $ssctxffc->number = $api['data'][0]['opencode'];
            $ssctxffc->receive_time = strtotime($api['data'][0]['opentime']);
            $ssctxffc->period = $api['data'][0]['expect'];
            $ssctxffc->status = 1;
                  $ssctxffc->color_id =9;
            $behind_period = substr($api['data'][0]['expect'],-2);
            if($behind_period == '84'){
                $time = date('Ymd',time());
                $ssctxffc->behind_period  = $time.'01';
                $ssctxffc->save();//保存
            }else{
                $ssctxffc->behind_period = $ssctxffc->period+1;//拿到前端显示下一期的开奖期号
                $ssctxffc->save();//保存
            }
            $lottery = new Lottery();
            $lottery->Winning(9);
        }
    }




    /**
     * 江西11选5获取接口数据
     */
    public function actionJx11x5()
    {
        $redis = new \Redis(); //实例化redis
        $redis->connect('127.0.0.1', '6379'); //建立redis服务连接
        $url=file_get_contents("http://e.apiplus.net/newly.do?token=t64fb3909e113c8d2k&code=jx11x5&format=json");
        $api = json_decode($url, true);
        //拿到期号到数据库里面去查，看有没有这个期号
        $ssctxffc = Jx11x5::findOne(['period'=>$api['data'][0]['expect']]);
        if (!$ssctxffc){//期号存在
            $date = date('H:i:s');
            $redis->set('jx11x5',$date);
            $redis->expire('jx11x5',600);
            $ssctxffc = new Jx11x5();
            $ssctxffc->time = time();
            $ssctxffc->name = '江西11选5';
            $ssctxffc->number = $api['data'][0]['opencode'];
            $ssctxffc->receive_time = strtotime($api['data'][0]['opentime']);
            $ssctxffc->period = $api['data'][0]['expect'];
            $ssctxffc->status = 1;
                            $ssctxffc->color_id =6;
            $behind_period = substr($api['data'][0]['expect'],-2);
            if($behind_period == '78'){
                $time = date('Ymd',time());
                $ssctxffc->behind_period  = $time.'01';
                $ssctxffc->save();//保存
            }else{
                $ssctxffc->behind_period = $ssctxffc->period+1;//拿到前端显示下一期的开奖期号
                $ssctxffc->save();//保存
            }
            $lottery = new Lottery();
            $lottery->Winning(6);
        }
    }



    /**
     * 江苏11选5获取接口数据
     */
    public function actionJs11x5()
    {
        $redis = new \Redis(); //实例化redis
        $redis->connect('127.0.0.1', '6379'); //建立redis服务连接
        $url=file_get_contents("http://e.apiplus.net/newly.do?token=t64fb3909e113c8d2k&code=gd11x5&format=json");
        $api = json_decode($url, true);
        //拿到期号到数据库里面去查，看有没有这个期号
        $ssctxffc = Js11x5::findOne(['period'=>$api['data'][0]['expect']]);
        if (!$ssctxffc){//期号存在
            $date = date('H:i:s');
            $redis->set('js11x5',$date);
            $redis->expire('js11x5',600);
            $ssctxffc = new Js11x5();
            $ssctxffc->time = time();
            $ssctxffc->name = '江苏11选5';
            $ssctxffc->number = $api['data'][0]['opencode'];
            $ssctxffc->receive_time = strtotime($api['data'][0]['opentime']);
            $ssctxffc->period = $api['data'][0]['expect'];
            $ssctxffc->status = 1;
                                      $ssctxffc->color_id =115;
            $behind_period = substr($api['data'][0]['expect'],-2);
            if($behind_period == '82'){
                $time = date('Ymd',time());
                $ssctxffc->behind_period  = $time.'01';
                $ssctxffc->save();//保存
            }else{
                $ssctxffc->behind_period = $ssctxffc->period+1;//拿到前端显示下一期的开奖期号
                $ssctxffc->save();//保存
            }
            $lottery = new Lottery();
            $lottery->Winning(115);
        }
    }



    /**
     * 上海11选5获取接口数据
     */
    public function actionSh11x5()
    {
        $redis = new \Redis(); //实例化redis
        $redis->connect('127.0.0.1', '6379'); //建立redis服务连接
        $url=file_get_contents("http://e.apiplus.net/newly.do?token=t64fb3909e113c8d2k&code=gd11x5&format=json");
        $api = json_decode($url, true);
        //拿到期号到数据库里面去查，看有没有这个期号
        $ssctxffc = Sh11x5::findOne(['period'=>$api['data'][0]['expect']]);
        if (!$ssctxffc){//期号存在
            $date = date('H:i:s');
            $redis->set('sh11x5',$date);
            $redis->expire('sh11x5',600);
            $ssctxffc = new Sh11x5();
            $ssctxffc->time = time();
            $ssctxffc->name = '上海11选5';
            $ssctxffc->number = $api['data'][0]['opencode'];
            $ssctxffc->receive_time = strtotime($api['data'][0]['opentime']);
            $ssctxffc->period = $api['data'][0]['expect'];
            $ssctxffc->status = 1;
                        $ssctxffc->color_id =22;
            $behind_period = substr($api['data'][0]['expect'],-2);
            if($behind_period == '90'){
                $time = date('Ymd',time());
                $ssctxffc->behind_period  = $time.'01';
                $ssctxffc->save();//保存
            }else{
                $ssctxffc->behind_period = $ssctxffc->period+1;//拿到前端显示下一期的开奖期号
                $ssctxffc->save();//保存
            }
            $lottery = new Lottery();
            $lottery->Winning(22);
        }
    }
}