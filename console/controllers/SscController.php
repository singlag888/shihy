<?php

namespace console\controllers;

use backend\models\Gfffc;
use frontend\models\Cqssc;
use frontend\models\Ssctxffc;
use frontend\models\Tjssc;
use frontend\models\Xjssc;
use yii\console\Controller;
use frontend\models\Lottery;

class SscController extends  Controller
{
    //解决ajax报400错误
    public $enableCsrfValidation = false;

    /**
     * 重庆时时彩获取接口数据
     */
    public function actionCqssc()
    {
        $redis = new \Redis(); //实例化redis
        $redis->connect('127.0.0.1', '6379'); //建立redis服务连接
        $url=file_get_contents("http://e.apiplus.net/newly.do?token=t64fb3909e113c8d2k&code=cqssc&format=json");
        $api = json_decode($url, true);
            //拿到期号到数据库里面去查，看有没有这个期号
            $ssctxffc = Cqssc::findOne(['period'=>$api['data'][0]['expect']]);
            if (!$ssctxffc){//期号存在
                $date = date('H:i:s');
                    if($date >= '10:00:00' && $date <= '22:00:00'){
                        $redis->set('cqssc',$date);
                        $redis->expire('cqssc',600);
                    }else{
                        $redis->set('cqssc',$date);
                        $redis->expire('cqssc',300);
                    }
                    $ssctxffc = new Cqssc();
                    $ssctxffc->time = time();
                    $ssctxffc->name = '重庆时时彩';
                    $ssctxffc->number = $api['data'][0]['opencode'];
                    $ssctxffc->receive_time = strtotime($api['data'][0]['opentime']);
                    $ssctxffc->period = $api['data'][0]['expect'];
                    $ssctxffc->status = 1;
              		$ssctxffc->color_id = 1;
                    $behind_period = substr($api['data'][0]['expect'],-3);
                    if($behind_period == '120'){
                        $time = date('Ymd',time());
                        $ssctxffc->behind_period  = $time.'001';
                        $ssctxffc->save();//保存
                    }else{
                        $ssctxffc->behind_period = $ssctxffc->period+1;//拿到前端显示下一期的开奖期号
                        $ssctxffc->save();//保存
                    }
                    $lottery = new Lottery();
                    $lottery->Winning(1);
            }
    }




    /**
     * 天津时时彩获取接口数据
     */
    public function actionTjssc()
    {
        $redis = new \Redis(); //实例化redis
        $redis->connect('127.0.0.1', '6379'); //建立redis服务连接
        $url=file_get_contents("http://e.apiplus.net/newly.do?token=t64fb3909e113c8d2k&code=tjssc&format=json");
        $api = json_decode($url, true);
        //拿到期号到数据库里面去查，看有没有这个期号
        $ssctxffc = Tjssc::findOne(['period'=>$api['data'][0]['expect']]);
        if (!$ssctxffc){//期号存在
            $date = date('H:i:s');
            $redis->set('tjssc',$date);
            $redis->expire('tjssc',600);
            $ssctxffc = new Tjssc();
            $ssctxffc->time = time();
            $ssctxffc->name = '天津时时彩';
            $ssctxffc->number = $api['data'][0]['opencode'];
            $ssctxffc->receive_time = strtotime($api['data'][0]['opentime']);
            $ssctxffc->period = $api['data'][0]['expect'];
            $ssctxffc->status = 1;
                     $ssctxffc->color_id = 3;
            $behind_period = substr($api['data'][0]['expect'],-3);
            if($behind_period == '084'){
                $time = date('Ymd',strtotime('+1 day'));
                $ssctxffc->behind_period  = $time.'001';
                $ssctxffc->save();//保存
            }else{
                $ssctxffc->behind_period = $ssctxffc->period+1;//拿到前端显示下一期的开奖期号
                $ssctxffc->save();//保存
            }
            $lottery = new Lottery();
            $lottery->Winning(3);
        }
    }





    /**
     * 新疆时时彩获取接口数据
     */
    public function actionXjssc()
    {
        $redis = new \Redis(); //实例化redis
        $redis->connect('127.0.0.1', '6379'); //建立redis服务连接
        $url=file_get_contents("http://e.apiplus.net/newly.do?token=t64fb3909e113c8d2k&code=xjssc&format=json");
        $api = json_decode($url, true);
        //拿到期号到数据库里面去查，看有没有这个期号
        $ssctxffc = Xjssc::findOne(['period'=>$api['data'][0]['expect']]);
        if (!$ssctxffc){//期号存在
            $date = date('H:i:s');
                $redis->set('xjssc',$date);
                $redis->expire('xjssc',600);
            $ssctxffc = new Xjssc();
            $ssctxffc->time = time();
            $ssctxffc->name = '新疆时时彩';
            $ssctxffc->number = $api['data'][0]['opencode'];
            $ssctxffc->receive_time = strtotime($api['data'][0]['opentime']);
            $ssctxffc->period = $api['data'][0]['expect'];
            $ssctxffc->status = 1;
                            $ssctxffc->color_id = 7;
            $behind_period = substr($api['data'][0]['expect'],-3);
            if($behind_period == '083'){
                $time = date('Ymd',strtotime('+1 day'));
                $ssctxffc->behind_period  = $time.'001';
                $ssctxffc->save();//保存
            }else{
                $ssctxffc->behind_period = $ssctxffc->period+1;//拿到前端显示下一期的开奖期号
                $ssctxffc->save();//保存
            }
            $lottery = new Lottery();
            $lottery->Winning(7);
        }
    }

}
